<?php

require('config/googleSignInConfig.php');

$googleClient->revokeToken();

session_start();
session_destroy();

header('Location: /');
