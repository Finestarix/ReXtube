<?php
session_start();

require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/core/subscriberController.php');

$userID = getSession()->id;
$friendID = $_POST['friend_id'];

$result = removeSubscriber($userID, $friendID);

