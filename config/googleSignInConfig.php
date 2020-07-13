<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

$envArr = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env');
$envArr->load();

$redirectURI = 'http://localhost:' . $_SERVER['SERVER_PORT'];
$googleSignInConfig = require_once('googleSignInConfig.php');

$googleClient = new Google_Client();

$googleClient->setApplicationName('ReXtube');

$googleClient->setClientId($_ENV['CLIENT_ID']);
$googleClient->setClientSecret($_ENV['CLIENT_SECRET']);
$googleClient->setRedirectUri($redirectURI);

$googleClient->addScope('email');
$googleClient->addScope('profile');
