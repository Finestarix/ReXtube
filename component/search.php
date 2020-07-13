<?php

require_once(dirname(__FILE__) . '/../controller/core/videoController.php');
require_once(dirname(__FILE__) . '/../controller/core/viewController.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

$searchVideos = getSearchVideo($_GET['key']);
?>

<div class="w-100 p-3">

    <?php
    while ($searchVideo = $searchVideos->fetch_object()) {
        $userUpload = getUserByID($searchVideo->user_id);
        $videoView = getTotalViewByVideoID($searchVideo->id);

        $videoTitle = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
            htmlspecialchars($searchVideo->title, ENT_QUOTES));
        $videoPreviewImage = '/video/' . $searchVideo->user_id . '/' . $searchVideo->id . '/image.jpg';
        $videoDate = date("F j, Y", strtotime($searchVideo->date));
        $videoDescription = strlen($searchVideo->description) > 80 ?
            (substr($searchVideo->description, 0, 80) . '...') : $searchVideo->description;
        $videoDescription = preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|strong|s|p|br))&gt;#', '<\1>',
            htmlspecialchars($videoDescription, ENT_QUOTES));
        ?>

        <a style="cursor:pointer; text-decoration: none; color: black"
           class="d-flex flex-row position-relative mt-3"
           href="watch?id=<?= $searchVideo->id ?>">

            <div class="mr-3">
                <img style="width: 250px"
                     src="<?= $videoPreviewImage ?>"
                     alt="image">
            </div>

            <div>
                <div class="h5"><?= $videoTitle ?></div>
                <div><?= $userUpload->name ?> - <?= $videoView->totalView ?> views - <?= $videoDate ?></div>
                <div><?= $videoDescription ?></div>
            </div>

        </a>

    <?php } ?>

</div>

