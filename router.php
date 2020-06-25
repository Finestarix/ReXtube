<?php

include('controller/routerController.php');
include_once('util/uriHelper.php');

$controllerURI = new RouterController();
$URI = getURI()['path'];

if (method_exists($controllerURI, $URI)) {
    $controllerURI->$URI();
} else
    $controllerURI->index();