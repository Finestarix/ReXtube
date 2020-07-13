<?php

require_once('controller/core/videoController.php');
require_once('controller/core/viewController.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

$userVideos = getHomeVideo();
?>

<div style="flex-wrap: wrap"
     class="d-flex flex-row m-3 justify-content-between">

    <?php
    while ($userVideo = $userVideos->fetch_object()) {
        $userUpload = getUserByID($userVideo->user_id);
        $videoView = getTotalViewByVideoID($userVideo->id);

        $videoTitle = strlen($userVideo->title) > 17 ?
            (substr($userVideo->title, 0, 17) . '...') : $userVideo->title;
        $videoTitle = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
            htmlspecialchars($videoTitle, ENT_QUOTES));

        $videoPreviewImage = '/video/' . $userVideo->user_id . '/' . $userVideo->id . '/image.jpg';
        $videoDate = date("M j, Y", strtotime($userVideo->date));
        ?>

        <a style="cursor:pointer; text-decoration: none; color: black; width: fit-content"
           class="d-flex flex-column mb-3"
           href="watch?id=<?= $userVideo->id ?>">

            <div class="w-auto">
                <img style="width: 250px"
                     src="<?= $videoPreviewImage ?>"
                     alt="image">
            </div>

            <div class="d-flex flex-row mt-2">
                <div class="mr-2 ">
                    <img style="width: 40px; border-radius: 100%"
                         src="<?= $userUpload->image ?>"
                         alt="image">
                </div>
                <div>
                    <div class="h5 m-0"><?= $videoTitle ?></div>
                    <div><?= $userUpload->name ?></div>
                    <div><?= $videoView->totalView ?> views - <?= $videoDate ?></div>
                </div>
            </div>

        </a>

    <?php } ?>

</div>

