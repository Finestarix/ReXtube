<?php
require_once('controller/core/videoController.php');
require_once('controller/core/viewController.php');

$searchVideos = getSearchVideo($_GET['key']);
?>

<div class="w-100 p-3">

    <?php
    while ($searchVideo = $searchVideos->fetch_object()) {
        $userUpload = getUserByID($searchVideo->user_id);
        $videoView = getTotalViewByVideoID($searchVideo->id);

        $videoPreviewImage = '/video/' . $searchVideo->user_id . '/' . $searchVideo->id . '/image.jpg';
        $videoDate = date("F j, Y", strtotime($searchVideo->date));
        $videoDescription = strlen($searchVideo->description) > 80 ?
            (substr($searchVideo->description, 0, 80) . '...') : $searchVideo->description;
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
                <div class="h5"><?= $searchVideo->title ?></div>
                <div><?= $userUpload->name ?> - <?= $videoView->totalView ?> views - <?= $videoDate ?></div>
                <div><?= $videoDescription ?></div>
            </div>

        </a>

    <?php } ?>

</div>

