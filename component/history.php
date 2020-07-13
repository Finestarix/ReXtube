<?php

require_once(dirname(__FILE__) . '/../controller/core/historiesController.php');
require_once(dirname(__FILE__) . '/../controller/core/videoController.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

$userHistories = getHistoriesByUserID($currentUser->id);
?>

<div class="w-100 p-3">

    <?php
    while ($userHistory = $userHistories->fetch_object()) {
        $userVideo = getVideoByID($userHistory->video_id);
        $userUpload = getUserByID($userVideo->user_id);

        $videoTitle = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
            htmlspecialchars($userVideo->title, ENT_QUOTES));
        $videoPreviewImage = '/video/' . $userVideo->user_id . '/' . $userVideo->id . '/image.jpg';
        $videoDate = date("F j, Y", strtotime($userHistory->date));
        ?>

        <a style="cursor:pointer; text-decoration: none; color: black"
           class="d-flex flex-row position-relative mt-3"
           href="watch?id=<?= $userVideo->id ?>">

            <div class="mr-3">
                <img style="width: 250px"
                     src="<?= $videoPreviewImage ?>"
                     alt="image">
            </div>

            <div>
                <div class="h5"><?= $videoTitle ?></div>
                <div><?= $userUpload->name ?></div>
                <div>Last Watch: <?= $videoDate ?></div>
            </div>

        </a>

    <?php } ?>

</div>

