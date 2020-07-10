<?php
require_once('controller/sessionController.php');
require_once('controller/userController.php');
require_once('controller/videoController.php');
require_once('controller/viewController.php');
require_once('controller/likeController.php');
require_once('controller/dislikeController.php');
require_once('controller/subscriberController.php');
require_once('controller/commentController.php');
require_once('controller/repliesController.php');

$video = getVideoByID($_GET['id']);
$videoUser = getUserByID($video->user_id);
$videoComments = getCommentByVideoID($video->id);

$videoName = $video->title;
$videoDescription = $video->description;
$videoDate = date("M j, Y", strtotime($video->date));
$videoPath = '/video/' . $video->user_id . '/' . $video->id . '/video.mp4';

$totalView = getTotalViewByVideoID($video->id);
$totalLike = getTotalLikeByVideoID($video->id);
$totalDislike = getTotalDislikeByVideoID($video->id);
$totalSubscriber = getTotalUserSubscriber($videoUser->id);

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
                <?= $totalView->totalView ?> views - <?= $videoDate ?>
            </div>

            <div style="border-width: 3px !important;"
                 class="d-flex flex-row border-bottom pb-1">

                <div class="d-flex flex-row ml-3 align-items-center">
                    <i class="fa fa-thumbs-up mr-2"></i>
                    <div><b><?= $totalLike->totalLike ?></b></div>
                </div>

                <div class="d-flex flex-row ml-3 align-items-center">
                    <i class="fa fa-thumbs-down mr-2"></i>
                    <div><b><?= $totalDislike->totalDislike ?></b></div>
                </div>

            </div>
        </div>

        <div class="d-flex flex-row justify-content-between pt-3 pb-3 border-bottom">

            <div class="d-flex flex-row">
                <img style="border-radius: 100%; width: 50px; height: 50px;"
                     src="<?= $videoUser->image ?>"
                     alt="userImage">

                <div class="ml-3">
                    <div><b><?= $videoUser->name ?></b></div>
                    <div><?= $totalSubscriber->Total ?> subscribers</div>

                    <div><?= $videoDescription ?></div>
                </div>

            </div>

            <div>
                <div style="background-color: #ff0000; border-radius: 7%;"
                     class="text-white p-2">
                    SUBSCRIBE
                </div>
            </div>

        </div>

        <div class="mt-3">
            <div class="d-flex flex-row">
                <img style="border-radius: 100%; width: 50px; height: 50px;"
                     src="<?= $user->userImage ?>"
                     alt="userImage">

                <div class="ml-3 d-flex align-items-center w-100">
                    <textarea style="outline: none; border: none; border-bottom: #484848 solid 1px; width: inherit; "
                              placeholder="Add a public comment..."></textarea>
                </div>
            </div>

            <div style="background-color: #065fd4; float: right; border-radius: 7%;"
                 class="text-white p-2 mt-3">
                POST
            </div>
        </div>

        <div class="mt-5 pt-3 w-100">
            <?php foreach ($videoComments as $comment) {
                $commentUser = getUserByID($comment['user_id']);
                $commentDate = date("M j, Y", strtotime($comment['date']));

                $replies = getRepliesByCommentID($comment['id']);
                ?>

                <div class="mt-3 w-100">
                    <div class="d-flex flex-row">
                        <img style="border-radius: 100%; width: 50px; height: 50px;"
                             src="<?= $commentUser->image ?>"
                             alt="userImage">

                        <div class="ml-3 w-100">
                            <div><b><?= $commentUser->name ?></b> <?= $commentDate ?></div>
                            <div><?= $comment['text'] ?></div>

                            <?php foreach ($replies as $reply) {
                                $replyUser = getUserByID($reply['user_id']);
                                $replyDate = date("M j, Y", strtotime($reply['date']));
                                ?>

                                <div class="mt-3">
                                    <div class="d-flex flex-row">
                                        <img style="border-radius: 100%; width: 50px; height: 50px;"
                                             src="<?= $replyUser->image ?>"
                                             alt="userImage">

                                        <div class="ml-3">
                                            <div><b><?= $replyUser->name ?></b> <?= $commentDate ?></div>
                                            <div><?= $reply['text'] ?></div>
                                        </div>
                                    </div>
                                </div>


                                <?php
                            }
                            ?>

                            <div class="mt-3">
                                <div class="d-flex flex-row">
                                    <img style="border-radius: 100%; width: 50px; height: 50px;"
                                         src="<?= $user->userImage ?>"
                                         alt="userImage">

                                    <div class="ml-3 d-flex align-items-center w-100">
                                        <textarea
                                                style="outline: none; border: none;
                                                       border-bottom: #484848 solid 1px; width: 100%;"
                                                placeholder="Add a public reply..."></textarea>
                                    </div>
                                </div>

                                <div style="background-color: #065fd4; float: right; border-radius: 7%;"
                                     class="text-white p-2 mt-3">
                                    REPLY
                                </div>
                            </div>

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

