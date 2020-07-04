<?php

require_once(dirname(__FILE__) . '/../config/googleSignInConfig.php');

$googleClient->revokeToken();

session_start();
session_destroy();

header('Location: /');
