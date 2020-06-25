<?php
require_once('util/uriHelper.php');
require_once('util/reportHelper.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ReXtube Initialization Script</title>
    <link rel="shortcut icon" href="<?= getAsset('images/favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= getAsset('vendor/twbs/bootstrap/dist/css/bootstrap.css') ?>">
    <script src="<?= getAsset('vendor/components/jquery/jquery.js'); ?>"></script>
    <script src="<?= getAsset('vendor/twbs/bootstrap/dist/js/bootstrap.js'); ?>"></script>
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
                $URI = getAsset('');

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

            $tableName = "2 tables (app_config, users)";

            $tableDropQueries = [
                "DROP TABLE IF EXISTS `app_config`",
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
                )"
            ];

            $tableInsertQueries = [
                "INSERT INTO `app_config` (`key`, `value`) VALUES (?, ?)",
                "INSERT INTO `users` (`id`, `name`, `email`, `image`) VALUES (?, ?, ?, ?)"
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
                    "https://yt3.ggpht.com/-ykT9UUXcQZY/AAAAAAAAAAI/AAAAAAAAAAA/Ny28Fr4vl3o/s108-c-k-c0x00ffffff-no-rj-mo/photo.jpg"
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
                    for ($i = 0; $i < count($data); $i++) {
                        $params[] = &$data[$i];
                    }

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

            $URI = getAsset('');

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
