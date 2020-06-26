<?php

require ('vendor/autoload.php');

$envArr = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env');
$envArr->load();

$redirectURI = 'http://localhost:8080';
$googleSignInConfig = require_once('googleSignInConfig.php');

$googleClient = new Google_Client();

$googleClient->setApplicationName('ReXtube');

$googleClient->setClientId($_ENV['CLIENT_ID']);
$googleClient->setClientSecret($_ENV['CLIENT_SECRET']);
$googleClient->setRedirectUri($redirectURI);

$googleClient->addScope('email');
$googleClient->addScope('profile');



