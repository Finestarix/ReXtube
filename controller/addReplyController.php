<?php

require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/core/repliesController.php');
require_once(dirname(__FILE__) . '/core/CSRFController.php');
require_once(dirname(__FILE__) . '/../util/generatorHelper.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

session_start();

if (checkToken($_POST['CSRF_TOKEN']))
    $_SESSION['ERROR'] = 'Invalid CSRF Token !';
else if (!isset($_POST['text']) || !isset($_POST['comment_id']) || empty($_POST['comment_id']))
    $_SESSION['ERROR'] = 'Invalid Reply Request !';
else if (empty($_POST['text']))
    $_SESSION['ERROR'] = 'Reply can\'t be empty !';

if (isset($_SESSION['ERROR'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
}

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