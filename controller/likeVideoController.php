<?php
session_start();

require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/core/likeController.php');
require_once(dirname(__FILE__) . '/core/dislikeController.php');

$userID = getSession()->id;
$videoID = $_POST['video_id'];

if (isUserDislike($userID, $videoID)) {
    removeDislike($userID, $videoID);
    insertLike($userID, $videoID);
} else if (isUserLike($userID, $videoID)) {
    removeLike($userID, $videoID);
} else {
    insertLike($userID, $videoID);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
