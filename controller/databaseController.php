<?php

function getConnection() {
    $databaseConfig = include ('config/databaseConfig.php');

    return new mysqli(
        $databaseConfig['HOST'],
        $databaseConfig['USERNAME'],
        $databaseConfig['PASSWORD'],
        $databaseConfig['DATABASE_NAME'],
        $databaseConfig['PORT']
    );
}