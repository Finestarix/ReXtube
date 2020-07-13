<?php

require_once(dirname(__FILE__) . '/../controller/core/subscriberController.php');
require_once(dirname(__FILE__) . '/../controller/core/videoController.php');
require_once(dirname(__FILE__) . '/../controller/core/viewController.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

if (isset($_GET['id']))
    $currentUser = getUserByID($_GET['id']);

$userVideos = getVideoByUserID($currentUser->id);
$userSubscriber = getTotalUserSubscriber($currentUser->id);
?>

<div class="bg-light d-flex flex-column align-items-center justify-content-center mt-3">

    <div class="d-flex flex-row align-items-center pb-3 w-100">

        <img style="border-radius: 100%; width: 100px"
             class="mr-3 ml-5"
             src="<?= $currentUser->image ?>"
             alt="userImage">

        <div>
            <div class="h3"> <?= $currentUser->name ?> </div>
            <div class="h6"> <?= $userSubscriber->totalSubscriber ?> subscribers</div>
        </div>

    </div>

    <div class="w-100 mt-3">

        <?php
        while ($userVideo = $userVideos->fetch_object()) {

            $videoTitle = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
                htmlspecialchars($userVideo->title, ENT_QUOTES));
            $videoPreviewImage = '/video/' . $userVideo->user_id . '/' . $userVideo->id . '/image.jpg';
            $videoDate = date("F j, Y", strtotime($userVideo->date));
            $videoDescription = strlen($userVideo->description) > 80 ?
                (substr($userVideo->description, 0, 80) . '...') : $userVideo->description;
            $videoDescription = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
                htmlspecialchars($videoDescription, ENT_QUOTES));

            $videoView = getTotalViewByVideoID($userVideo->id);
            ?>

            <a style="cursor:pointer; text-decoration: none; color: black"
               class="d-flex flex-row mb-3 ml-5 position-relative"
               href="watch?id=<?= $userVideo->id ?>">

                <div class="mr-3">
                    <img style="width: 250px"
                         src="<?= $videoPreviewImage ?>"
                         alt="image">
                </div>

                <div>
                    <div class="h5"><?= $videoTitle ?></div>
                    <div><?= $videoView->totalView ?> views - <?= $videoDate ?></div>
                    <div><?= $videoDescription ?></div>
                </div>

            </a>

        <?php } ?>

    </div>

</div>