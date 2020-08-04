<?php

require_once(dirname(__FILE__) . '/../util/uriHelper.php');

checkURI(realpath(__FILE__));

session_start();

ini_set('error_reporting', '0');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ReXtube</title>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="/vendor/components/font-awesome/css/all.css">
    <link rel="stylesheet" href="/vendor/components/font-awesome/css/v4-shims.css">
    <script src="/vendor/components/jquery/jquery.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.js"></script>
    <script src="/vendor/ckeditor/ckeditor/ckeditor.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;

            color: #484848;
        }
    </style>
</head>
