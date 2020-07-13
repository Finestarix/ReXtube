<?php

require_once(dirname(__FILE__) . '/../util/generatorHelper.php');
require_once(dirname(__FILE__) . '/../config/googleSignInConfig.php');
require_once(dirname(__FILE__) . '/core/userController.php');
require_once(dirname(__FILE__) . '/core/sessionController.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

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

    $user = new stdClass();
    $user->id = generateUUID();
    $user->name = $name;
    $user->email = $email;
    $user->image = $image;

    if ($searchUser != null) {
        if (!validateUserData($searchUser, $user))
            updateUser($searchUser->id, $user);

        $user->id = $searchUser->id;
    } else
        insertUser($user);

    setSession($user);
}

header('Location: /');
