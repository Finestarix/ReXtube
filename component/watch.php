<?php

require_once(dirname(__FILE__) . '/../controller/core/sessionController.php');
require_once(dirname(__FILE__) . '/../controller/core/userController.php');
require_once(dirname(__FILE__) . '/../controller/core/videoController.php');
require_once(dirname(__FILE__) . '/../controller/core/viewController.php');
require_once(dirname(__FILE__) . '/../controller/core/likeController.php');
require_once(dirname(__FILE__) . '/../controller/core/dislikeController.php');
require_once(dirname(__FILE__) . '/../controller/core/subscriberController.php');
require_once(dirname(__FILE__) . '/../controller/core/commentController.php');
require_once(dirname(__FILE__) . '/../controller/core/repliesController.php');
require_once(dirname(__FILE__) . '/../controller/core/historiesController.php');
require_once(dirname(__FILE__) . '/../controller/core/CSRFController.php');
require_once(dirname(__FILE__) . '/../util/generatorHelper.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

regenerateToken();

checkURI(realpath(__FILE__));

$video = getVideoByID($_GET['id']);
$videoUser = getUserByID($video->user_id);
$videoComments = getCommentByVideoID($video->id);
$videoView = getTotalViewByVideoID($video->id);
$videoLike = getTotalLikeByVideoID($video->id);
$videoDislike = getTotalDislikeByVideoID($video->id);
$videoSubscriber = getTotalUserSubscriber($videoUser->id);

$videoName = $video->title;
$videoName = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
    htmlspecialchars($videoName, ENT_QUOTES));
$videoDescription = $video->description;
$videoDescription = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
    htmlspecialchars($videoDescription, ENT_QUOTES));
$videoDate = date("M j, Y", strtotime($video->date));
$videoPath = '/video/' . $video->user_id . '/' . $video->id . '/video.mp4';

$randomVideos = getRandomVideo($video->id);

if (getSession() != NULL) {
    if (!isUserHistory($currentUser->id, $video->id)) {
        $history = new stdClass();
        $history->id = generateUUID();
        $history->video_id = $video->id;
        $history->user_id = $currentUser->id;
        $datetime = new DateTime();
        $history->date = date_format($datetime, 'Y-m-d H:i:s');
        insertHistory($history);
    }

    if (!isUserView($currentUser->id, $video->id))
        insertView($currentUser->id, $video->id);

    $isUserLike = isUserLike($currentUser->id, $video->id);
    $isUserDislike = isUserDislike($currentUser->id, $video->id);
}

?>

<div style="background-color: black"
     class="d-flex justify-content-center w-100">
    <video style="outline: none"
           height="500" controls muted autoplay>
        <source src="<?= $videoPath ?>">
    </video>
</div>

<div style="width: 100%;"
     class="d-flex flex-row">

    <div style="width: 70%;"
         class="p-4">

        <div class="h5"><?= $videoName ?></div>

        <div class="d-flex justify-content-between border-bottom">

            <div class="d-flex flex-row">
                <?= $videoView->totalView ?> views - <?= $videoDate ?>
            </div>

            <div id="like-container"
                 style="border-width: 3px !important;"
                 class="d-flex flex-row border-bottom pb-1">

                <div class="d-flex flex-row ml-3 align-items-center"
                     style="cursor: pointer;">
                    <i class="fa fa-thumbs-up mr-2"
                       id="like-button"></i>
                    <div><b><?= $videoLike->totalLike ?></b></div>
                </div>

                <div class="d-flex flex-row ml-3 align-items-center"
                     style="cursor: pointer;">
                    <i class="fa fa-thumbs-down mr-2"
                       id="dislike-button"></i>
                    <div><b><?= $videoDislike->totalDislike ?></b></div>
                </div>

            </div>
        </div>

        <div class="d-flex flex-row justify-content-between pt-3 pb-3 border-bottom">

            <div class="d-flex flex-row">
                <a href="channel.php?id=<?= $videoUser->id ?>">
                    <img style="border-radius: 100%; width: 50px; height: 50px;"
                         src="<?= $videoUser->image ?>"
                         alt="userImage">
                </a>

                <div class="ml-3">
                    <div><b><?= $videoUser->name ?></b></div>
                    <div><?= $videoSubscriber->totalSubscriber ?> subscribers</div>
                    <div><?= $videoDescription ?></div>
                </div>

            </div>

            <div>
                <?php
                if (getSession() != NULL) {
                    if ($video->user_id != $currentUser->id) {
                        $isSubscribe = isSubscribe($currentUser->id, $video->user_id);
                        if ($isSubscribe->totalSubscriber == 0) {
                            ?>
                            <div style="background-color: #ff0000; border-radius: 7%; cursor: pointer;"
                                 class="text-white p-2"
                                 id="add-subscription">
                                SUBSCRIBE
                            </div>
                        <?php } else if ($isSubscribe->totalSubscriber == 1) { ?>
                            <div style="background-color: #dee2e6; border-radius: 7%; cursor: pointer;"
                                 class="p-2"
                                 id="remove-subscription">
                                SUBSCRIBED
                            </div>
                        <?php } ?>
                    <?php }
                } ?>
            </div>

        </div>

        <?php if (getSession() != NULL) { ?>
            <form class="mt-3"
                  action="/controller/addCommentController.php"
                  method="post">

                <div class="d-flex flex-row">
                    <a href="channel.php?id=<?= $currentUser->id ?>">
                        <img style="border-radius: 100%; width: 50px; height: 50px;"
                             src="<?= $currentUser->image ?>"
                             alt="userImage">
                    </a>

                    <div class="ml-3 d-flex align-items-center w-100">
                    <textarea style="outline: none; border: none; border-bottom: #484848 solid 1px; width: inherit; "
                              name="text"
                              placeholder="Add a public comment..."></textarea>
                    </div>

                    <input type="hidden" name="video_id" value="<?= $video->id ?>">
                </div>

                <input type="hidden"
                       name="CSRF_TOKEN"
                       value="<?= getToken() ?>">

                <button style="background-color: #065fd4; float: right; border-radius: 7%;"
                        class="btn btn-primary mt-3">
                    POST
                </button>

            </form>
        <?php } ?>

        <div class="mt-3 pt-3 w-100 h5">
            Comments
        </div>

        <div class="w-100">
            <?php
            while ($videoComment = $videoComments->fetch_object()) {
                $commentUser = getUserByID($videoComment->user_id);
                $videoCommentText = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
                    htmlspecialchars($videoComment->text, ENT_QUOTES));

                $commentReplies = getRepliesByCommentID($videoComment->id);

                $commentDate = date("M j, Y", strtotime($videoComment->date));
                ?>

                <div class="mt-3 w-100">
                    <div class="d-flex flex-row">
                        <a href="channel.php?id=<?= $commentUser->id ?>">
                            <img style="border-radius: 100%; width: 50px; height: 50px;"
                                 src="<?= $commentUser->image ?>"
                                 alt="userImage">
                        </a>

                        <div class="ml-3 w-100">
                            <div><b><?= $commentUser->name ?></b> <?= $commentDate ?></div>
                            <div><?= $videoCommentText ?></div>

                            <?php
                            while ($commentReply = $commentReplies->fetch_object()) {
                                $replyUser = getUserByID($commentReply->user_id);
                                $commentReplyText = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
                                    htmlspecialchars($commentReply->text, ENT_QUOTES));

                                $replyDate = date("M j, Y", strtotime($commentReply->date));
                                ?>

                                <div class="mt-3">
                                    <div class="d-flex flex-row">
                                        <a href="channel.php?id=<?= $replyUser->id ?>">
                                            <img style="border-radius: 100%; width: 50px; height: 50px;"
                                                 src="<?= $replyUser->image ?>"
                                                 alt="userImage">
                                        </a>

                                        <div class="ml-3">
                                            <div><b><?= $replyUser->name ?></b> <?= $replyDate ?></div>
                                            <div><?= $commentReplyText ?></div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                            ?>

                            <?php if (getSession() != NULL) { ?>
                                <form class="mt-3"
                                      action="/controller/addReplyController.php"
                                      method="post">

                                    <div class="d-flex flex-row">
                                        <img style="border-radius: 100%; width: 50px; height: 50px;"
                                             src="<?= $currentUser->image ?>"
                                             alt="userImage">

                                        <div class="ml-3 d-flex align-items-center w-100">
                                        <textarea
                                                style="outline: none; border: none;
                                                       border-bottom: #484848 solid 1px; width: 100%;"
                                                name="text"
                                                placeholder="Add a public reply..."></textarea>
                                        </div>

                                        <input type="hidden" name="comment_id" value="<?= $videoComment->id ?>">
                                    </div>

                                    <input type="hidden"
                                           name="CSRF_TOKEN"
                                           value="<?= getToken() ?>">

                                    <button style="background-color: #065fd4; float: right; border-radius: 7%;"
                                            class="btn btn-primary mt-3">
                                        REPLY
                                    </button>

                                </form>
                            <?php } ?>

                        </div>
                    </div>
                </div>

                <?php
            }
            ?>

        </div>

    </div>

    <div style="width: 30%;"
         class="p-4">
        <div>
            Up Next

            <?php
            while ($randomVideo = $randomVideos->fetch_object()) {
                $videoTitle = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
                    htmlspecialchars($randomVideo->title, ENT_QUOTES));

                $previewImage = '/video/' . $randomVideo->user_id . '/' . $randomVideo->id . '/image.jpg';
                $userUpload = getUserByID($randomVideo->user_id);
                ?>

                <a style="cursor:pointer; text-decoration: none; color: black"
                   class="d-flex flex-row position-relative mt-3"
                   href="watch?id=<?= $randomVideo->id ?>">

                    <div class="mr-3">
                        <img style="width: 150px"
                             src="<?= $previewImage ?>"
                             alt="image">
                    </div>

                    <div>
                        <div><b><?= $videoTitle ?></b></div>
                        <div><?= $userUpload->name ?></div>
                    </div>

                </a>

            <?php } ?>

        </div>
    </div>
</div>

<script>

    <?php if ($isUserLike != NULL) { ?>
    $("#like-button").attr('style', 'color: #007bff !important');
    $("#like-container").attr('style', 'border-color: #007bff !important');
    <?php } else if ($isUserDislike != NULL) { ?>
    $("#dislike-button").attr('style', 'color: #007bff !important');
    $("#like-container").attr('style', 'border-color: #007bff !important');
    <?php } ?>

    $("#add-subscription").click(function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append("friend_id", "<?= $video->user_id ?>");
        formData.append("CSRF_TOKEN", "<?= getToken() ?>");

        $.ajax({
            url: "/controller/addSubscriptionController.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                location.reload();
            }
        });
    });

    $("#remove-subscription").click(function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append("friend_id", "<?= $video->user_id ?>");
        formData.append("CSRF_TOKEN", "<?= getToken() ?>");

        $.ajax({
            url: "/controller/removeSubscriptionController.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                location.reload();
            }
        });
    });

    $("#like-button").click(function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append("video_id", "<?= $video->id ?>");
        formData.append("CSRF_TOKEN", "<?= getToken() ?>");

        $.ajax({
            url: "/controller/likeVideoController.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                location.reload();
            }
        });
    });

    $("#dislike-button").click(function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append("video_id", "<?= $video->id ?>");
        formData.append("CSRF_TOKEN", "<?= getToken() ?>");

        $.ajax({
            url: "/controller/dislikeVideoController.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                location.reload();
            }
        });
    });

</script>