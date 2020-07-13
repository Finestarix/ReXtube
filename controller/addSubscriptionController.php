<?php

require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/core/subscriberController.php');
require_once(dirname(__FILE__) . '/core/CSRFController.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

session_start();

if (checkToken($_POST['CSRF_TOKEN']))
    header('Location: ' . $_SERVER['HTTP_REFERER']);

$userID = getSession()->id;
$friendID = $_POST['friend_id'];

$result = insertSubscriber($userID, $friendID);
