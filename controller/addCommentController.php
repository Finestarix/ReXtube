<?php

require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/core/commentController.php');
require_once(dirname(__FILE__) . '/core/CSRFController.php');
require_once(dirname(__FILE__) . '/../util/generatorHelper.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

session_start();

if (checkToken($_POST['CSRF_TOKEN']))
    $_SESSION['ERROR'] = 'Invalid CSRF Token !';
else if (!isset($_POST['text']) || !isset($_POST['video_id']) || empty($_POST['video_id']))
    $_SESSION['ERROR'] = 'Invalid Comment Request !';
else if (empty($_POST['text']))
    $_SESSION['ERROR'] = 'Comment can\'t be empty !';

if (isset($_SESSION['ERROR'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
}

$commentID = generateUUID();
$userID = getSession()->id;
$videoID = $_POST['video_id'];
$commentVideo = $_POST['text'];
$date = getdate();

$comment = new stdClass();
$comment->id = $commentID;
$comment->user_id = $userID;
$comment->video_id = $videoID;
$comment->text = $commentVideo;
$datetime = new DateTime();
$comment->date = date_format($datetime, 'Y-m-d H:i:s');

insertComment($comment);

header('Location: ' . $_SERVER['HTTP_REFERER']);