<?php

class RouterController {

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

    public function logout() {
        include 'controller/signOutController.php';
    }

}