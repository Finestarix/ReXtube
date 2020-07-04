<?php
require_once('controller/signInController.php');
require_once('controller/sessionController.php');

$user = getSession();

if ($user == null) {
    $path = getURI()['path'];
    if ($path == 'channel' || $path == 'upload')
        header('Location: ' . $googleClient->createAuthUrl());
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

        <a class="form-inline position-relative">

            <label>
                <input style="width: 30em"
                       class="form-control mr-3"
                       placeholder="Search"
                       type="search">
            </label>

            <i style="cursor: pointer; right: 30px"
               class="fa fa-search position-absolute"
               aria-hidden="true"></i>

        </a>

    </div>

    <div class="d-inline-flex align-items-center">

        <a style="font-size: 1.2em; text-decoration: none; color: black;"
           class="fa fa-video mr-3"
           href="upload"></a>

        <?php if ($user == null) { ?>
            <a style="border: 1px solid #007bff; color: #007bff; text-decoration: none;"
               class="d-inline-flex align-items-center p-2"
               href="<?= $googleClient->createAuthUrl() ?>">

                <i style="font-size: 1.2em"
                   class="fa fa-user mr-3"
                   aria-hidden="true"></i>

                <div style="font-size: 16px">
                    SIGN IN
                </div>
            </a>
        <?php } else { ?>
            <img style="width: 35px; border-radius: 100%; cursor: pointer"
                 id="profile"
                 src="<?= $user->userImage ?>"
                 alt="Icon">
        <?php } ?>
    </div>

    <?php if ($user != null) { ?>
        <div style="position:fixed; display: none !important; right: 55px; top: 0;"
             id="profile-dropdown"
             class="bg-light border p-3 flex-column">

            <div class="d-inline-flex align-items-center
                            border-bottom p-2">
                <div>
                    <img style="width: 45px; border-radius: 100%"
                         src="<?= $user->userImage ?>"
                         alt="Icon">
                </div>

                <div class="flex-column ml-3">
                    <p class="m-0"><b><?= $user->userName ?></b></p>
                    <p class="m-0"><?= $user->userEmail ?></p>
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

        <a style="cursor:pointer; text-decoration: none;"
              class="d-inline-flex align-items-center pb-3"
              id="subscription"
              href="subscription">

            <div>
                <i style="font-size: 1.6em;"
                   class="fa fa-plus"></i>
            </div>

            <div class="ml-3">
                Subscription
            </div>

        </a>

        <div class="mt-3 mb-4 border-top"></div>

        <?php if ($user == null) { ?>

            <p>Sign in to like videos, <br>comment, and subscribe.</p>

            <a style="border: 1px solid #007bff; color: #007bff; text-decoration: none"
               class="d-inline-flex align-items-center p-2"
               href="<?= $googleClient->createAuthUrl() ?>">

                <i style="font-size: 1.2em"
                   class="fa fa-user mr-3"
                   aria-hidden="true"></i>

                <div style="font-size: 16px">
                    SIGN IN
                </div>
            </a>
        <?php } else { ?>

            <form style="cursor:pointer;"
                  class="d-inline-flex align-items-center pb-3"
                  id="history"
                  action="history"
                  method="post">

                <div>
                    <i style="font-size: 1.6em;"
                       class="fa fa-history"></i>
                </div>

                <div class="ml-3">
                    History
                </div>

            </form>

        <?php } ?>

    </div>

    <script>
        <?php if($user != null) { ?>

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

    </script>

</nav>
