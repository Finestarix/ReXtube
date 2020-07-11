<?php
require_once('controller/core/sessionController.php');
require_once('controller/core/userController.php');
require_once('controller/core/videoController.php');
require_once('controller/core/viewController.php');
require_once('controller/core/likeController.php');
require_once('controller/core/dislikeController.php');
require_once('controller/core/subscriberController.php');
require_once('controller/core/commentController.php');
require_once('controller/core/repliesController.php');

$video = getVideoByID($_GET['id']);
$videoUser = getUserByID($video->user_id);
$videoComments = getCommentByVideoID($video->id);

$videoName = $video->title;
$videoDescription = $video->description;
$videoDate = date("M j, Y", strtotime($video->date));
$videoPath = '/video/' . $video->user_id . '/' . $video->id . '/video.mp4';

if (!isUserView($currentUser->id, $video->id))
    insertView($currentUser->id, $video->id);
$videoView = getTotalViewByVideoID($video->id);

$videoSubscriber = getTotalUserSubscriber($videoUser->id);

$videoLike = getTotalLikeByVideoID($video->id);
$isUserLike = isUserLike($currentUser->id, $video->id);

$videoDislike = getTotalDislikeByVideoID($video->id);
$isUserDislike = isUserDislike($currentUser->id, $video->id);

$randomVideos = getRandomVideo($video->id);
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
                <?php } ?>

            </div>

        </div>

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

            <button style="background-color: #065fd4; float: right; border-radius: 7%;"
                    class="btn btn-primary mt-3">
                POST
            </button>

        </form>

        <div class="mt-5 pt-3 w-100">
            <?php
            while ($videoComment = $videoComments->fetch_object()) {
                $commentUser = getUserByID($videoComment->user_id);
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
                            <div><?= $videoComment->text ?></div>

                            <?php
                            while ($commentReply = $commentReplies->fetch_object()) {
                                $replyUser = getUserByID($commentReply->user_id);

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
                                            <div><?= $commentReply->text ?></div>
                                        </div>
                                    </div>
                                </div>


                                <?php
                            }
                            ?>

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

                                <button style="background-color: #065fd4; float: right; border-radius: 7%;"
                                        class="btn btn-primary mt-3">
                                    REPLY
                                </button>

                            </form>

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

            <?php foreach ($randomVideos as $randomVideo) {
                $previewImage = '/video/' . $randomVideo['user_id'] . '/' . $randomVideo['id'] . '/image.jpg';
                $userUpload = getUserByID($randomVideo['user_id']);
                ?>

                <a style="cursor:pointer; text-decoration: none; color: black"
                   class="d-flex flex-row position-relative mt-3"
                   href="watch?id=<?= $randomVideo['id'] ?>">

                    <div class="mr-3">
                        <img style="width: 150px"
                             src="<?= $previewImage ?>"
                             alt="image">
                    </div>

                    <div>
                        <div><b><?= $randomVideo['title'] ?></b></div>
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