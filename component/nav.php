<?php

require_once(dirname(__FILE__) . '/../controller/signInController.php');
require_once(dirname(__FILE__) . '/../controller/core/videoController.php');
require_once(dirname(__FILE__) . '/../controller/core/sessionController.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

$currentUser = getSession();

$path = getURI()['path'];
if ($currentUser == null) {
    if ($path == 'channel' || $path == 'upload' || $path == 'history')
        header('Location: ' . $googleClient->createAuthUrl());
} else if ($path == 'watch' && !isset($_GET['id'])) {
    header('Location:  /');
} else if ($path == 'watch' && getVideoByID($_GET['id']) == null) {
    header('Location:  /');
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light
    flex-row align-items-center justify-content-between sticky-top
    border-bottom">

    <div class="flex-row align-items-center">

        <i style="font-size: 1.6em; vertical-align: middle; cursor: pointer;"
           id="menu"
           class="fa fa-bars"
           aria-hidden="true"></i>

        <a href="/">
            <img style="width: 150px"
                 src="/images/logo.png"
                 alt="ReXtube">
        </a>

    </div>

    <div class="flex-row align-items-center justify-content-center">

        <div class="form-inline position-relative">

            <label>
                <input style="width: 30em"
                       class="form-control mr-3 glyphicon glyphicon-remove-circle"
                       placeholder="Search"
                       id="search-input"
                       type="text">
            </label>

            <a style="cursor: pointer; right: 30px"
               class="fa fa-search position-absolute"
               id="search-button"
               aria-hidden="true"></a>

        </div>

    </div>

    <div class="d-inline-flex align-items-center">

        <a style="font-size: 1.2em; text-decoration: none; color: black;"
           class="fa fa-video mr-3"
           href="upload"></a>

        <?php if ($currentUser == null) { ?>
            <a style="border: 1px solid #007bff; color: #007bff; text-decoration: none;"
               class="d-inline-flex align-items-center p-2"
               href="<?= $googleClient->createAuthUrl() ?>">

                <i style="font-size: 1.2em; color: #007bff;"
                   class="fa fa-user mr-3"
                   aria-hidden="true"></i>

                <div style="font-size: 16px; color: #007bff">
                    SIGN IN
                </div>
            </a>
        <?php } else { ?>
            <img style="width: 35px; border-radius: 100%; cursor: pointer"
                 id="profile"
                 src="<?= $currentUser->image ?>"
                 alt="Icon">
        <?php } ?>
    </div>

    <?php if ($currentUser != null) { ?>
        <div style="position:fixed; display: none !important; right: 55px; top: 0;"
             id="profile-dropdown"
             class="bg-light border p-3 flex-column">

            <div class="d-inline-flex align-items-center
                            border-bottom p-2">
                <div>
                    <img style="width: 45px; border-radius: 100%"
                         src="<?= $currentUser->image ?>"
                         alt="Icon">
                </div>

                <div class="flex-column ml-3">
                    <p class="m-0"><b><?= $currentUser->name ?></b></p>
                    <p class="m-0"><?= $currentUser->email ?></p>
                </div>

            </div>

            <a style="cursor:pointer; text-decoration: none; color: black;"
               class="d-inline-flex align-items-center p-2 pl-3 pt-3"
               id="channel"
               href="channel">

                <div>
                    <i style="font-size: 1.6em;"
                       class="fa fa-user"></i>
                </div>

                <div class="ml-4 pl-2">
                    Your Channel
                </div>

            </a>

            <a style="cursor:pointer; text-decoration: none; color: black;"
               class="d-inline-flex align-items-center p-2 pl-3"
               id="logout"
               href="logout">

                <div>
                    <i style="font-size: 1.6em;"
                       class="fa fa-sign-out"></i>
                </div>

                <div class="ml-4 pl-2">
                    Sign Out
                </div>

            </a>

        </div>
    <?php } ?>

    <div style="position:fixed; display: none !important; left: 0; top: 0; height: 100vh"
         id="menu-dropdown"
         class="bg-light border p-3 flex-column">

        <div class="flex-row align-items-center mb-5">

            <i style="font-size: 1.6em; vertical-align: middle; cursor: pointer;"
               class="fa fa-bars"
               aria-hidden="true"></i>

            <a href="/">
                <img style="width: 150px"
                     src="/images/logo.png"
                     alt="ReXtube">
            </a>

        </div>

        <a style="cursor:pointer; text-decoration: none;"
           class="d-inline-flex align-items-center pb-3"
           id="home"
           href="/">

            <div>
                <i style="font-size: 1.6em;"
                   class="fa fa-home"></i>
            </div>

            <div class="ml-3">
                Home
            </div>

        </a>

        <a style="cursor:pointer; text-decoration: none;"
           class="d-inline-flex align-items-center pb-3"
           id="trending"
           href="trending">

            <div>
                <i style="font-size: 1.6em;"
                   class="fa fa-line-chart"></i>
            </div>

            <div class="ml-3">
                Trending
            </div>

        </a>

        <div class="mt-3 mb-4 border-top"></div>

        <?php if ($currentUser == null) { ?>

            <p>Sign in to like videos, <br>comment, and subscribe.</p>

            <a style="border: 1px solid #007bff; color: #007bff; text-decoration: none"
               class="d-inline-flex align-items-center p-2"
               href="<?= $googleClient->createAuthUrl() ?>">

                <i style="font-size: 1.2em; color: #007bff;"
                   class="fa fa-user mr-3"
                   aria-hidden="true"></i>

                <div style="font-size: 16px; color: #007bff">
                    SIGN IN
                </div>
            </a>
        <?php } else { ?>

            <a style="cursor:pointer; text-decoration: none;"
               class="d-inline-flex align-items-center pb-3"
               id="history"
               href="history">

                <div>
                    <i style="font-size: 1.6em;"
                       class="fa fa-history"></i>
                </div>

                <div class="ml-3">
                    History
                </div>

            </a>

        <?php } ?>

    </div>

    <script>
        <?php if($currentUser != null) { ?>

        let isProfileOpen = false;
        const profile = document.getElementById('profile');
        const dropdownProfile = document.getElementById('profile-dropdown');
        profile.addEventListener('click', () => {
            dropdownProfile.style.display = (isProfileOpen) ? 'none' : 'flex';
            isProfileOpen = !isProfileOpen;
        });

        <?php } ?>

        let isMenuOpen = false;
        const menu = document.getElementById('menu');
        const dropdownMenu = document.getElementById('menu-dropdown');
        menu.addEventListener('click', () => {
            dropdownMenu.style.display = 'flex';
            isMenuOpen = !isMenuOpen;
        });
        dropdownMenu.addEventListener('click', () => {
            dropdownMenu.style.display = 'none';
            isMenuOpen = !isMenuOpen;
        });

        $("#search-button").click(function (e) {
            const key = $("#search-input").val();
            if (key !== "")
                location.href = "search.php?key=" + key;
        });
    </script>

</nav>
