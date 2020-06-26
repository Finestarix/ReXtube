<?php

require('config/googleSignInConfig.php');
require('userController.php');

if (!isset($_GET["code"]))
    return;

$googleToken = $googleClient->fetchAccessTokenWithAuthCode($_GET["code"]);

if (!isset($googleToken['error'])) {
    $googleClient->setAccessToken($googleToken['access_token']);
    $_SESSION['ACCESS_TOKEN'] = $googleToken['access_token'];

    $googleService = new Google_Service_Oauth2($googleClient);
    $googleData = $googleService->userinfo->get();
    $_SESSION['NAME'] = $googleData['given_name'] . $googleData['family_name'];
    $_SESSION['EMAIL'] = $googleData['email'];
    $_SESSION['PROFILE'] = $googleData['picture'];


    var_dump(getUserByEmail($_SESSION['EMAIL']));
    var_dump($_SESSION['PROFILE']);
}

header('Location: /');
