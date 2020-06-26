<?php
require('controller/signInController.php');
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

        <form class="form-inline">

            <input style="width: 30em"
                   class="form-control mr-3"
                   placeholder="Search"
                   type="search">

            <i style="cursor: pointer"
               class="fa fa-search"
               aria-hidden="true"></i>

        </form>

    </div>

    <div class="d-inline-flex align-items-center">

        <i style="font-size: 1.2em"
           class="fa fa-video mr-3"
           aria-hidden="true"></i>

        <?php if (!isset($_SESSION['ACCESS_TOKEN'])) { ?>
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
            <img style="width: 35px; border-radius: 100%; cursor: pointer"
                 id="profile"
                 src="<?= $_SESSION['PROFILE'] ?>"
                 alt="Icon">
        <?php } ?>
    </div>

    <?php if (isset($_SESSION['ACCESS_TOKEN'])) { ?>
        <div style="position:fixed; display: none !important; right: 100px; top: 0;"
             id="profile-dropdown"
             class="bg-light border p-3 flex-column">

            <div class="d-inline-flex align-items-center
                            border-bottom p-2">
                <div>
                    <img style="width: 45px; border-radius: 100%"
                         src="<?= $_SESSION['PROFILE'] ?>"
                         alt="Icon">
                </div>

                <div class="flex-column ml-3">
                    <p class="m-0"><b><?= $_SESSION['NAME'] ?></b></p>
                    <p class="m-0"><?= $_SESSION['EMAIL'] ?></p>
                </div>

            </div>

            <form style="cursor:pointer;"
                  class="d-inline-flex align-items-center p-2 pl-3 pt-3"
                  id="channel"
                  action="channel"
                  method="post">

                <div>
                    <i style="font-size: 1.6em;"
                       class="fa fa-user"></i>
                </div>

                <div class="ml-4 pl-2"
                     id="channel">
                    Your Channel
                </div>

            </form>

            <form style="cursor:pointer;"
                  class="d-inline-flex align-items-center p-2 pl-3"
                  id="logout"
                  action="logout"
                  method="post">

                <div>
                    <i style="font-size: 1.6em;"
                       class="fa fa-sign-out"></i>
                </div>

                <div class="ml-4 pl-2">
                    Sign Out
                </div>

            </form>

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

        <form style="cursor:pointer;"
              class="d-inline-flex align-items-center pb-3"
              id="home"
              action="/"
              method="post">

            <div>
                <i style="font-size: 1.6em;"
                   class="fa fa-home"></i>
            </div>

            <div class="ml-3">
                Home
            </div>

        </form>

        <form style="cursor:pointer;"
              class="d-inline-flex align-items-center pb-3"
              id="home"
              action="trending"
              method="post">

            <div>
                <i style="font-size: 1.6em;"
                   class="fa fa-line-chart"></i>
            </div>

            <div class="ml-3">
                Trending
            </div>

        </form>

        <form style="cursor:pointer;"
              class="d-inline-flex align-items-center pb-3"
              id="home"
              action="subscription"
              method="post">

            <div>
                <i style="font-size: 1.6em;"
                   class="fa fa-plus"></i>
            </div>

            <div class="ml-3">
                Subscription
            </div>

        </form>

        <div class="mt-3 mb-4 border-top"></div>

        <?php if (!isset($_SESSION['ACCESS_TOKEN'])) { ?>

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
                  id="home"
                  action="subscription"
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
        if (<?= isset($_SESSION['ACCESS_TOKEN']) ?>) {

            let isProfileOpen = false;
            const profile = document.getElementById('profile');
            const dropdownProfile = document.getElementById('profile-dropdown');
            profile.addEventListener('click', () => {
                dropdownProfile.style.display = (isProfileOpen) ? 'none' : 'flex';
                isProfileOpen = !isProfileOpen;
            });

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

            const logout = document.getElementById('logout');
            logout.addEventListener('click', () => {
                logout.submit();
            });

        }


    </script>

</nav>
