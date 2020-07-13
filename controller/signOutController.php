<?php

require_once(dirname(__FILE__) . '/../config/googleSignInConfig.php');
require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

$googleClient->revokeToken();

session_start();
session_destroy();

header('Location: /');
