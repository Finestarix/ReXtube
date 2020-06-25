<?php
require_once('util/uriHelper.php');
require_once('util/reportHelper.php');
require('config/googleSignInConfig.php');

$loginButton = '';

$googleClient->revokeToken();
session_destroy();

if (isset($_GET["code"])) {

    $googleToken = $googleClient->fetchAccessTokenWithAuthCode($_GET["code"]);

    if (!isset($googleToken['error'])) {
        $googleClient->setAccessToken($googleToken['access_token']);

        $_SESSION['access_token'] = $googleToken['access_token'];

        $googleService = new Google_Service_Oauth2($googleClient);
        $googleData = $googleService->userinfo->get();

        if (!empty($googleData['given_name']))
            $_SESSION['FIRST_NAME'] = $googleData['given_name'];

        if (!empty($googleData['family_name']))
            $_SESSION['LAST_NAME'] = $googleData['family_name'];

        if (!empty($googleData['email']))
            $_SESSION['EMAIL'] = $googleData['email'];

        if (!empty($googleData['picture']))
            $_SESSION['PROFILE'] = $googleData['picture'];
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ReXtube</title>
    <link rel="shortcut icon" href="<?= getAsset('images/favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= getAsset('vendor/twbs/bootstrap/dist/css/bootstrap.css') ?>">
    <script src="<?= getAsset('vendor/components/jquery/jquery.js'); ?>"></script>
    <script src="<?= getAsset('vendor/twbs/bootstrap/dist/js/bootstrap.js'); ?>"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-light flex-row align-items-center justify-content-between">

    <div class="flex-row align-items-center justify-content-center">

        <button class="bg-light border-0" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a href="<?= getAsset(''); ?>">
            <img style="width: 150px" src="<?= getAsset('images/logo.png'); ?>" alt="ReXtube">
        </a>

    </div>

    <div class="flex-row align-items-center justify-content-center">
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control" type="search" placeholder="Search">
            <button class="btn btn-outline-dark my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>

    <div class="align-items-end">
        <?php
        if (!isset($_SESSION['access_token']))
            echo '<a href="'.$googleClient->createAuthUrl().'">Log In</a>';
        else
            echo '<img style="width: 40px; border-radius: 100%" src="'.$_SESSION['PROFILE'].'" alt="Icon">';
        ?>
    </div>
</nav>

</body>
</html>