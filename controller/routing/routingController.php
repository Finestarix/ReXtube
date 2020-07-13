<?php

require_once(dirname(__FILE__) . '/../../util/uriHelper.php');

checkURI(realpath(__FILE__));

class RoutingController {

    public function index() {
        include 'index.php';
    }

    public function initialize() {
        include 'initialize.php';
    }

    public function channel() {
        include 'channel.php';
    }

    public function upload() {
        include 'upload.php';
    }

    public function trending() {
        include 'trending.php';
    }

    public function watch() {
        include 'watch.php';
    }

    public function search() {
        include 'search.php';
    }

    public function history() {
        include 'history.php';
    }

    public function logout() {
        include 'controller/signOutController.php';
    }

}