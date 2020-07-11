<?php
session_start();

require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/core/repliesController.php');
require_once(dirname(__FILE__) . '/../util/UUIDHelper.php');

$replyID = generateUUID();
$userID = getSession()->id;
$commentID = $_POST['comment_id'];
$replyComment = $_POST['text'];
$date = getdate();

$reply = new stdClass();
$reply->id = $replyID;
$reply->user_id = $userID;
$reply->comment_id = $commentID;
$reply->text = $replyComment;
$datetime = new DateTime();
$reply->date = date_format($datetime, 'Y-m-d H:i:s');

insertReply($reply);

header('Location: ' . $_SERVER['HTTP_REFERER']);