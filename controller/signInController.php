<?php

require_once(dirname(__FILE__) . '/../util/UUIDHelper.php');
require_once(dirname(__FILE__) . '/../config/googleSignInConfig.php');
require_once(dirname(__FILE__) . '/userController.php');
require_once(dirname(__FILE__) . '/sessionController.php');

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

    if ($searchUser != null) {
        if (!validateUserData($searchUser, $user))
            updateUser($searchUser->id, $user);

        $user->userID = $searchUser->id;
    } else
        insertUser($user);

    setSession($user);
}

header('Location: /');
