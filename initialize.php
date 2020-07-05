<?php
require_once('util/uriHelper.php');
require_once('util/reportHelper.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('component/header.php') ?>
</head>
<body class="bg-dark">
<div class="container-fluid mt-3 mb-3">
    <header class="jumbotron text-center">
        <h1 class="title">ReXtube Initialization Script</h1>
    </header>
    <article>
        <?php
        $reportPreflightName = 'Preflight Report';
        $reportSQLConnectName = 'MySQLi Connect Report';
        $reportSQLDropName = 'Table Schema Dropping Report';
        $reportSQLCreateName = 'Table Schema Creation Report';
        $reportSQLPreparationName = 'Statement Preparation Report';
        $reportSeederName = 'Table Seeder Report';
        $reportGenerateName = 'Initialization Completed';

        $isAlreadyInitialized = false;
        $isForce = false;

        $databaseRoot = 'information_schema';
        $databaseConfig = require_once('config/databaseConfig.php');
        $preflightConnection = new mysqli(
            $databaseConfig['HOST'],
            $databaseConfig['USERNAME'],
            $databaseConfig['PASSWORD'],
            $databaseRoot,
            $databaseConfig['PORT']
        );

        if ($preflightConnection->connect_error) {
            $errorMessage = 'Pre-initialization connect failed: ' . $preflightConnection->connect_error;
            die(createReport($reportPreflightName, $errorMessage));
        }

        $createDatabaseQuery = "CREATE DATABASE IF NOT EXISTS " . $databaseConfig['DATABASE_NAME'];
        $preflightConnection->query($createDatabaseQuery);

        $preflightConnection->select_db($databaseConfig['DATABASE_NAME']);

        $initializeQuery = "SELECT `value` FROM app_config WHERE `key` = 'initialized' AND value = 1";
        $initializeResult = $preflightConnection->query($initializeQuery);
        if ($initializeResult) {
            $fetchResult = $initializeResult->fetch_assoc();
            if ($fetchResult && $fetchResult['value'])
                $isAlreadyInitialized = true;
        }

        $preflightConnection->close();

        if ($isAlreadyInitialized) {

            $isRequestForce = isset($_REQUEST['force']);

            if ($isRequestForce) {
                $successMessage = "Data has been initialized. Force initialization is issued. This initialization script will re-initialize the application.";
                echo createReport($reportPreflightName, $successMessage);

                $isForce = true;
            } else {
                $URI = '/';

                $successMessage = "Data has been initialized. Please add force parameter if you want to re-initialize the application.";
                $otherMessage = sprintf(
                    "<a href='?force' class='btn btn-warning mt-2'>Force Initialization</a>
                     <a href='%s' class='btn btn-primary mt-2'>Open Website</a>", $URI
                );

                echo createReport($reportPreflightName, [$successMessage, $otherMessage]);
            }
        } else {
            $successMessage = "Application is ready for initialization.";
            echo createReport($reportPreflightName, $successMessage);
        }

        if (!$isAlreadyInitialized || $isForce) {

            $connectionCharset = 'utf8';

            $connection = new mysqli(
                $databaseConfig['HOST'],
                $databaseConfig['USERNAME'],
                $databaseConfig['PASSWORD'],
                $databaseConfig['DATABASE_NAME'],
                $databaseConfig['PORT']
            );
            $connection->set_charset($connectionCharset);

            if ($connection->connect_error) {
                $errorMessage = "Failed to connect to config: " . $connection->connect_error;
                die(createReport($reportSQLConnectName, $errorMessage));
            }

            $errorMessage = "Successfully established config connection to " . $databaseConfig['DATABASE_NAME'] .
                "@" . $databaseConfig['HOST'] . " with username " . $databaseConfig['USERNAME'];
            echo createReport($reportSQLConnectName, $errorMessage);

            $tableName = "7 tables (app_config, users, subscriber, videos, like_detail, dislike_detail, view_detail)";

            $tableDropQueries = [
                "DROP TABLE IF EXISTS `app_config`",
                "DROP TABLE IF EXISTS `like_detail`",
                "DROP TABLE IF EXISTS `dislike_detail`",
                "DROP TABLE IF EXISTS `view_detail`",
                "DROP TABLE IF EXISTS `subscribers`",
                "DROP TABLE IF EXISTS `videos`",
                "DROP TABLE IF EXISTS `users`"
            ];

            $tableCreateQueries = [
                "CREATE TABLE `app_config` (
                    `key` VARCHAR(15),
                    `value` BOOLEAN,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`key`)
                )",
                "CREATE TABLE `users` (
                  `id` char(40) NOT NULL,
                  `name` varchar(255) NOT NULL,
                  `email` varchar(255) NOT NULL,
                  `image` varchar(255) NOT NULL,
                  PRIMARY KEY (`id`)
                )",
                "CREATE TABLE `subscribers` (
                  `user_id` char(40) NOT NULL,
                  `friend_id` char(40) NOT NULL,
                  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
                  FOREIGN KEY (`friend_id`) REFERENCES `users`(`id`),
                  PRIMARY KEY (`user_id`, `friend_id`)
                )",
                "CREATE TABLE `videos` (
                  `id` char(40) NOT NULL,
                  `user_id` char(40) NOT NULL,
                  `title` varchar(255) NOT NULL,
                  `description` varchar(2000) NOT NULL,
                  `date` DATETIME NOT NULL,
                  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
                  PRIMARY KEY (`id`)
                )",
                "CREATE TABLE `like_detail` (
                  `video_id` char(40) NOT NULL,
                  `user_id` char(40) NOT NULL,
                  FOREIGN KEY (`video_id`) REFERENCES `videos`(`id`),
                  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
                  PRIMARY KEY (`video_id`, `user_id`)
                )",
                "CREATE TABLE `dislike_detail` (
                  `video_id` char(40) NOT NULL,
                  `user_id` char(40) NOT NULL,
                  FOREIGN KEY (`video_id`) REFERENCES `videos`(`id`),
                  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
                  PRIMARY KEY (`video_id`, `user_id`)
                )",
                "CREATE TABLE `view_detail` (
                  `video_id` char(40) NOT NULL,
                  `user_id` char(40) NOT NULL,
                  FOREIGN KEY (`video_id`) REFERENCES `videos`(`id`),
                  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
                  PRIMARY KEY (`video_id`, `user_id`)
                )",
            ];

            $tableInsertQueries = [
                "INSERT INTO `app_config` (`key`, `value`) VALUES (?, ?)",
                "INSERT INTO `users` (`id`, `name`, `email`, `image`) VALUES (?, ?, ?, ?)",
                "INSERT INTO `subscribers` (`user_id`, `friend_id`) VALUES (?, ?)",
                "INSERT INTO `videos` (`id`, `user_id`, `title`, `description`, `date`) VALUES (?, ?, ?, ?, ?)",
                "INSERT INTO `like_detail` (`user_id`, `video_id`) VALUES (?, ?)",
                "INSERT INTO `dislike_detail` (`user_id`, `video_id`) VALUES (?, ?)",
                "INSERT INTO `view_detail` (`user_id`, `video_id`) VALUES (?, ?)",
            ];

            foreach ($tableDropQueries as $tableDropQuery)
                if (!$connection->query($tableDropQuery)) {
                    $errorMessage = "Drop tables failed: " . $connection->error;
                    die(createReport($reportSQLDropName, $errorMessage));
                }

            $successMessage = "Successfully drops all " . $tableName;
            echo createReport($reportSQLDropName, $successMessage);

            foreach ($tableCreateQueries as $tableCreateQuery)
                if (!$connection->query($tableCreateQuery)) {
                    $errorMessage = "Table creation failed: " . $connection->error;
                    die(createReport($reportSQLCreateName, $errorMessage));
                }

            $successMessage = "Successfully creates all " . $tableName;
            echo createReport($reportSQLCreateName, $successMessage);

            $appConfigSeeder = [
                [
                    "si",
                    "initialized",
                    true
                ]
            ];

            $userSeeder = [
                [
                    "ssss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "Ignatius Renaldy",
                    "irenaldyleonarto@gmail.com",
                    "https://lh3.googleusercontent.com/a-/AOh14Gh_W4Akm61Ga58uIdilytUall7FnkPDnYRy_D5fRg"
                ],
                [
                    "ssss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "Adrestia Persephone",
                    "adrestiapersephone@gmail.com",
                    "https://lh3.googleusercontent.com/a-/AOh14GjBvJsfCDNmLK2lNxbvRxjPGjQ69O6nfSKCzPpY"
                ],
                [
                    "ssss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "BLACKPINK",
                    "blackpink@gmail.com",
                    "https://yt3.ggpht.com/a/AATXAJwgwcqoiYKn7umZJ1KFC1M2tCjqc_Di3i0d3Ooj7Q=s100-c-k-c0xffffffff-no-rj-mo"
                ],
                [
                    "ssss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "Misellia Ikwan",
                    "misellia.ikwan@gmail.com",
                    "https://yt3.ggpht.com/a/AATXAJzWmrairpbFnDsV3pmLo8QE0ICud0H593lwBluQOQ=s100-c-k-c0xffffffff-no-rj-mo"
                ],
                [
                    "ssss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "Big Hit Labels",
                    "big.hit.labels@gmail.com",
                    "https://yt3.ggpht.com/a/AATXAJxBjKTvadXLRjKBIaWrrmKIDTKar0e4eyhj91Bm=s100-c-k-c0xffffffff-no-rj-mo"
                ],
            ];

            $subscriberSeeder = [
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "233173a0-bd48-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "a37699ec-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "a37699ec-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "233173a0-bd48-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "a37699ec-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "233173a0-bd48-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "a37699ec-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "233173a0-bd48-11ea-b3de-0242ac130004"
                ],
            ];

            $videoSeeder = [
                [
                    "sssss",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "BLACKPINK - How You Like That",
                    "BLACKPINK - How You Like That",
                    "2020-06-26 10:00:00"
                ],
                [
                    "sssss",
                    "a775f28e-5726-4503-8b54-c525e95e61b0",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "BLACKPINK - Stay",
                    "BLACKPINK - Stay",
                    "2016-10-31 20:00:00"
                ],
                [
                    "sssss",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "BTS - Stay Gold",
                    "BTS - Stay Gold",
                    "2020-06-26 13:00:00 AM"
                ],
                [
                    "sssss",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "Final Fantasy 7 Remake - Official Trailer",
                    "Final Fantasy 7 Remake - Official Trailer",
                    "2020-04-30 12:00:00"
                ],
                [
                    "sssss",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "TIKTOK SING OFF",
                    "TIKTOK SING OFF",
                    "2020-06-20 16:30:00"
                ],
                [
                    "sssss",
                    "570f6f8e-9e4f-41e4-94e9-85d765bbbcc8",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "Greenland - Official Trailer [HD]",
                    "Greenland - Official Trailer [HD]",
                    "2020-03-10 14:00:00"
                ],
                [
                    "sssss",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "Kimetsu No Yai",
                    "Kimetsu No Yai",
                    "2019-12-29 18:30:00"
                ],
                [
                    "sssss",
                    "f408badf-0429-4518-8409-9012973a932c",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "Kingdom - Official Trailer [HD]",
                    "Kingdom - Official Trailer [HD]",
                    "2019-12-29 18:30:00"
                ],
                [
                    "sssss",
                    "5e90df93-9eb6-4a48-8078-4850c302c46a",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "Minecraft Nether Update - Official Trailer",
                    "Minecraft Nether Update - Official Trailer",
                    "2019-12-29 18:30:00"
                ],
                [
                    "sssss",
                    "f5f9e067-a02f-4986-af67-0cbc6a18fddf",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "Weird Genius - Lathi (ft. Sara Fajira) Official Music Video",
                    "Weird Genius - Lathi (ft. Sara Fajira) Official Music Video",
                    "2019-12-29 18:30:00"
                ]
            ];

            $likeDetailSeeder = [
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "a775f28e-5726-4503-8b54-c525e95e61b0"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "570f6f8e-9e4f-41e4-94e9-85d765bbbcc8"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "5e90df93-9eb6-4a48-8078-4850c302c46a"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "f5f9e067-a02f-4986-af67-0cbc6a18fddf"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "570f6f8e-9e4f-41e4-94e9-85d765bbbcc8"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "570f6f8e-9e4f-41e4-94e9-85d765bbbcc8"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "5e90df93-9eb6-4a48-8078-4850c302c46a"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "a775f28e-5726-4503-8b54-c525e95e61b0"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "570f6f8e-9e4f-41e4-94e9-85d765bbbcc8"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "5e90df93-9eb6-4a48-8078-4850c302c46a"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "f5f9e067-a02f-4986-af67-0cbc6a18fddf"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "a775f28e-5726-4503-8b54-c525e95e61b0"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ]
            ];

            $dislikeDetailSeeder = [
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "a775f28e-5726-4503-8b54-c525e95e61b0"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "f5f9e067-a02f-4986-af67-0cbc6a18fddf"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "5e90df93-9eb6-4a48-8078-4850c302c46a"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "f5f9e067-a02f-4986-af67-0cbc6a18fddf"
                ],
            ];

            $viewDetailSeeder = [
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "a775f28e-5726-4503-8b54-c525e95e61b0"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "570f6f8e-9e4f-41e4-94e9-85d765bbbcc8"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "5e90df93-9eb6-4a48-8078-4850c302c46a"
                ],
                [
                    "ss",
                    "242f6b8c-1b3b-4c13-9394-412778e58ec1",
                    "f5f9e067-a02f-4986-af67-0cbc6a18fddf"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "a775f28e-5726-4503-8b54-c525e95e61b0"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "570f6f8e-9e4f-41e4-94e9-85d765bbbcc8"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ],
                [
                    "ss",
                    "233173a0-bd48-11ea-b3de-0242ac130004",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "570f6f8e-9e4f-41e4-94e9-85d765bbbcc8"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "f5f9e067-a02f-4986-af67-0cbc6a18fddf"
                ],
                [
                    "ss",
                    "5f20dbe0-be91-11ea-b3de-0242ac130004",
                    "5e90df93-9eb6-4a48-8078-4850c302c46a"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "a775f28e-5726-4503-8b54-c525e95e61b0"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "570f6f8e-9e4f-41e4-94e9-85d765bbbcc8"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "5e90df93-9eb6-4a48-8078-4850c302c46a"
                ],
                [
                    "ss",
                    "a37699ec-be91-11ea-b3de-0242ac130004",
                    "f5f9e067-a02f-4986-af67-0cbc6a18fddf"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "cefd24b0-5198-4a65-9d20-1faaaffec4d5"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "a775f28e-5726-4503-8b54-c525e95e61b0"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "8012c37d-84b4-42e9-bcb6-03089a12858d"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "d3512d6f-255e-484d-8672-aa80e3dad7b7"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "e486ebef-9938-4a45-a316-a9ccfa7eab82"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "52cd3513-5e13-473c-aa41-7e068d4ba358"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "f408badf-0429-4518-8409-9012973a932c"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "5e90df93-9eb6-4a48-8078-4850c302c46a"
                ],
                [
                    "ss",
                    "cebd4e0c-be91-11ea-b3de-0242ac130004",
                    "f5f9e067-a02f-4986-af67-0cbc6a18fddf"
                ]
            ];

            $initializeDatas = [
                [
                    $tableInsertQueries[0],
                    $appConfigSeeder
                ],
                [
                    $tableInsertQueries[1],
                    $userSeeder
                ],
                [
                    $tableInsertQueries[2],
                    $subscriberSeeder
                ],
                [
                    $tableInsertQueries[3],
                    $videoSeeder
                ],
                [
                    $tableInsertQueries[4],
                    $likeDetailSeeder
                ],
                [
                    $tableInsertQueries[5],
                    $dislikeDetailSeeder
                ],
                [
                    $tableInsertQueries[6],
                    $viewDetailSeeder
                ]
            ];

            foreach ($initializeDatas as $initializeData) {

                $query = $initializeData[0];
                $datas = $initializeData[1];

                foreach ($datas as $data) {
                    $preparedStatement = $connection->prepare($query);

                    if (!$preparedStatement) {
                        $errorMessage = "Statement preparation failed: " . $connection->error;
                        die(createReport($reportSQLPreparationName, $errorMessage));
                    }

                    $params = [];
                    for ($i = 0; $i < count($data); $i++)
                        $params[] = &$data[$i];

                    call_user_func_array([$preparedStatement, "bind_param"], $params);

                    $preparedStatement->execute();
                    $preparedStatement->close();
                }
            }

            $connection->close();

            $successMessage = "Statement preparation successful";
            echo createReport($reportSQLPreparationName, $successMessage);

            echo createReport($reportSeederName, [
                "Users data entered (" . count($userSeeder) . " data(s))",
            ]);

            echo createReport($reportSeederName, [
                "Subscription data entered (" . count($subscriberSeeder) . " data(s))",
            ]);

            echo createReport($reportSeederName, [
                "Videos data entered (" . count($videoSeeder) . " data(s))",
            ]);

            echo createReport($reportSeederName, [
                "Like Detail data entered (" . count($likeDetailSeeder) . " data(s))",
            ]);

            echo createReport($reportSeederName, [
                "Dislike Detail data entered (" . count($dislikeDetailSeeder) . " data(s))",
            ]);

            echo createReport($reportSeederName, [
                "View Detail data entered (" . count($viewDetailSeeder) . " data(s))",
            ]);

            $URI = '/';

            $successMessage = "Initialization Database Complete";
            $otherMessage = sprintf(
                "<a href='%s' class='btn btn-primary mt-2'>Open Website</a>", $URI
            );

            echo createReport($reportGenerateName, [$successMessage, $otherMessage]);
        }
        ?>
    </article>
</body>
</html>
