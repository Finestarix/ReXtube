<?php

require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/core/likeController.php');
require_once(dirname(__FILE__) . '/core/dislikeController.php');
require_once(dirname(__FILE__) . '/core/CSRFController.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

session_start();

if (checkToken($_POST['CSRF_TOKEN']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);

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
