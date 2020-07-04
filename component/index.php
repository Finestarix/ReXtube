<?php
require_once('controller/videoController.php');

$userVideos = getHomeVideo();
?>

<div style="flex-wrap: wrap"
     class="d-flex flex-row m-3 justify-content-between">

    <?php
    foreach ($userVideos as $userVideo) {
        $previewImage = '/video/' . $userVideo['user_id'] . '/' . $userVideo['id'] . '/image.jpg';
        $userUpload = getUserByID($userVideo['user_id']);
        $title = strlen($userVideo['title']) > 17 ?
            (substr($userVideo['title'], 0, 17) . '...') : $userVideo['title'];
        $date = date("M j, Y", strtotime($userVideo['date']));
        ?>

        <a style="cursor:pointer; text-decoration: none; color: black; width: fit-content"
           class="d-flex flex-column mb-3"
           href="watch?id=<?= $userVideo['id'] ?>">

            <div class="w-auto">
                <img style="width: 250px"
                     src="<?= $previewImage ?>"
                     alt="image">
            </div>

            <div class="d-flex flex-row mt-2">
                <div class="mr-2">
                    <img style="width: 40px; border-radius: 100%"
                         src="<?= $userUpload->image ?>"
                         alt="image">
                </div>
                <div>
                    <div class="h5 m-0"><?= $title ?></div>
                    <div><?= $userUpload->name ?></div>
                    <div>0 view - <?= $date ?></div>
                </div>
            </div>

        </a>

    <?php } ?>

</div>

