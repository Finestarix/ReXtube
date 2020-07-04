<?php
require_once('controller/subscriberController.php');
require_once('controller/videoController.php');

$totalSubscriber = getTotalUserSubscriber($user->userID);
$userVideos = getVideoByUserID($user->userID);
?>

<div class="bg-light d-flex flex-column align-items-center justify-content-center mt-3">

    <div class="d-flex flex-row align-items-center pb-3 w-100">

        <img style="border-radius: 100%; width: 100px"
             class="mr-3 ml-5"
             src="<?= $user->userImage ?>"
             alt="userImage">

        <div>
            <div class="h3"> <?= $user->userName ?> </div>
            <div class="h6"> <?= $totalSubscriber->Total ?> subscribers</div>
        </div>

    </div>

    <div class="w-100 mt-3">

        <?php
        foreach ($userVideos as $userVideo) {
            $previewImage = '/video/' . $userVideo['user_id'] . '/' . $userVideo['id'] . '/image.jpg';
            $date = date("F j, Y", strtotime($userVideo['date']));
            $description = strlen($userVideo['description']) > 80 ?
                (substr($userVideo['description'], 0, 80) . '...') : $userVideo['description'];
            ?>

            <a style="cursor:pointer; text-decoration: none; color: black"
               class="d-flex flex-row mb-3 ml-5 position-relative"
               href="watch?id=<?= $userVideo['id'] ?>">

                <div class="mr-3">
                    <img style="width: 250px"
                         src="<?= $previewImage ?>"
                         alt="image">
                </div>

                <div>
                    <div class="h4"><?= $userVideo['title'] ?></div>
                    <div>0 view - <?= $date ?></div>
                    <div><?= $description ?></div>
                </div>

            </a>

        <?php } ?>

    </div>

</div>