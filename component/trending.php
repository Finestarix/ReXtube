<?php
require_once('controller/core/videoController.php');

$userVideos = getTrendingVideo();
?>

<div class="w-100 p-3">

    <?php
    while ($userVideo = $userVideos->fetch_object()) {
        $userUpload = getUserByID($userVideo->user_id);

        $videoPreviewImage = '/video/' . $userVideo->user_id . '/' . $userVideo->id . '/image.jpg';
        $videoDate = date("F j, Y", strtotime($userVideo->date));
        $videoDescription = strlen($userVideo->description) > 80 ?
            (substr($userVideo->description, 0, 80) . '...') : $userVideo->description;
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
                <div class="h5"><?= $userVideo->title ?></div>
                <div><?= $userUpload->name ?> - <?= $userVideo->totalView ?> views - <?= $videoDate ?></div>
                <div><?= $videoDescription ?></div>
            </div>

        </a>

    <?php } ?>

</div>

