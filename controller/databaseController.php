<?php

if (!function_exists('getConnection')) {
    function getConnection()
    {
        $databaseConfig = include(dirname(__FILE__) . '/../config/databaseConfig.php');

        return new mysqli(
            $databaseConfig['HOST'],
            $databaseConfig['USERNAME'],
            $databaseConfig['PASSWORD'],
            $databaseConfig['DATABASE_NAME'],
            $databaseConfig['PORT']
        );
    }
}