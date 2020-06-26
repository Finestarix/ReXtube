<?php

require('util/UUIDHelper.php');
require('config/googleSignInConfig.php');
require('userController.php');

if (!isset($_GET["code"]))
    return;

$googleToken = $googleClient->fetchAccessTokenWithAuthCode($_GET["code"]);

if (!isset($googleToken['error'])) {
    $googleClient->setAccessToken($googleToken['access_token']);

    $googleService = new Google_Service_Oauth2($googleClient);
    $googleData = $googleService->userinfo->get();

    $name = $googleData['given_name'] . ' ' . $googleData['family_name'];
    $email = $googleData['email'];
    $image = $googleData['picture'];

    $searchUser = getUserByEmail($googleData['email']);
    $user = new User(generateUUID(), $name, $email, $image);

    if ($searchUser) {
        if(!validateUserData($searchUser, $user))
            updateUser($searchUser->id, $user);
    } else
        insertUser($user);

    $_SESSION['ID'] = $searchUser->id;
    $_SESSION['NAME'] = $user->userName;
    $_SESSION['EMAIL'] = $user->userEmail;
    $_SESSION['IMAGE'] = $user->userImage;
}

header('Location: /');
