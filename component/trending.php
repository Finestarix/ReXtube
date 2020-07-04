<?php
require_once('controller/videoController.php');

$userVideos = getTrendingVideo();
?>

<div class="w-100 p-3">

    <?php
    foreach ($userVideos as $userVideo) {
        $previewImage = '/video/' . $userVideo['user_id'] . '/' . $userVideo['id'] . '/image.jpg';
        $date = date("F j, Y", strtotime($userVideo['date']));
        $userUpload = getUserByID($userVideo['user_id']);
        $description = strlen($userVideo['description']) > 80 ?
            (substr($userVideo['description'], 0, 80) . '...') : $userVideo['description'];
        ?>

        <a style="cursor:pointer; text-decoration: none; color: black"
           class="d-flex flex-row position-relative mt-3"
           href="watch?id=<?= $userVideo['id'] ?>">

            <div class="mr-3">
                <img style="width: 250px"
                     src="<?= $previewImage ?>"
                     alt="image">
            </div>

            <div>
                <div class="h5"><?= $userVideo['title'] ?></div>
                <div><?= $userUpload->name ?> - <?= $userVideo['totalView'] ?> views - <?= $date ?></div>
                <div><?= $description ?></div>
            </div>

        </a>

    <?php } ?>

</div>

