<?php

include('controller/routingController.php');
include_once('util/uriHelper.php');

$controllerURI = new RoutingController();
$URI = getURI()['path'];

if (method_exists($controllerURI, $URI)) {
    $controllerURI->$URI();
} else
    $controllerURI->index();