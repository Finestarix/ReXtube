<?php

require_once(dirname(__FILE__) . '/../../util/uriHelper.php');

checkURI(realpath(__FILE__));

if (!function_exists('getConnection')) {
    function getConnection()
    {
        $databaseConfig = include(dirname(__FILE__) . '/../../config/databaseConfig.php');

        $databaseConnection = new mysqli(
            $databaseConfig['HOST'],
            $databaseConfig['USERNAME'],
            $databaseConfig['PASSWORD'],
            $databaseConfig['DATABASE_NAME'],
            $databaseConfig['PORT']
        );
        $databaseConnection->set_charset('utf8mb4');

        return $databaseConnection;
    }
}