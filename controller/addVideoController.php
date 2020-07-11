<?php
session_start();

require_once(dirname(__FILE__) . '/../util/UUIDHelper.php');
require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/core/videoController.php');

if(isset($_POST['g-recaptcha-response']))
    $captcha = $_POST['g-recaptcha-response'];

if (!$captcha)
    $_SESSION['ERROR'] = 'You are robot !';
else if (empty($_POST['title']))
    $_SESSION['ERROR'] = 'Title can\'t be empty !';
else if (empty($_POST['description']))
    $_SESSION['ERROR'] = 'Description can\'t be empty !';
else if (empty($_FILES['video']['name']))
    $_SESSION['ERROR'] = 'Video can\'t be empty !';
else if (empty($_FILES['thumbnail']['name']))
    $_SESSION['ERROR'] = 'Thumbnail can\'t be empty !';
else if ($_FILES['video']['type'] !== 'video/mp4')
    $_SESSION['ERROR'] = 'Video extension must be mp4 !';
else if ($_FILES['video']['size'] > 524288000)
    $_SESSION['ERROR'] = 'Video size must be less than 500MB !';
else if ($_FILES['thumbnail']['type'] !== 'image/jpeg')
    $_SESSION['ERROR'] = 'Thumbnail extension must be jpg !';
else if ($_FILES['thumbnail']['size'] > 10485760)
    $_SESSION['ERROR'] = 'Thumbnail size must be less than 10MB !';

if (isset($_SESSION['ERROR'])) {
    header('Location: ../upload.php');
    die();
}

$UUID = generateUUID();
$user = getSession();

$directoryFolder = '../video/' . $user->id . '/' . $UUID . '/';

$video = new stdClass();
$video->id = $UUID;
$video->user_id = $user->id;
$video->title = $_POST['title'];
$video->description = $_POST['description'];
$datetime = new DateTime();
$video->date = date_format($datetime, 'Y-m-d H:i:s');
insertVideo($video);

mkdir($directoryFolder);
move_uploaded_file($_FILES['video']['tmp_name'], $directoryFolder . 'video.mp4');
move_uploaded_file($_FILES['thumbnail']['tmp_name'], $directoryFolder . 'image.jpg');

header('Location: ../channel.php');