<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

$envArr = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env');
$envArr->load();

return [
    /**
     * Database Server Host
     */
    'HOST' => $_ENV['HOST'],

    /**
     * Database Server Port
     */
    'PORT' => $_ENV['PORT'],

    /**
     * Database Username
     */
    'USERNAME' => $_ENV['USERNAME'],

    /**
     * Database Password
     */
    'PASSWORD' => $_ENV['PASSWORD'],

    /**
     * Database Name
     */
    'DATABASE_NAME' => 'phph3project'
];


