<?php
session_start();

require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/core/commentController.php');
require_once(dirname(__FILE__) . '/../util/UUIDHelper.php');

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