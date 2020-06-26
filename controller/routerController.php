<?php

class RouterController {

    public function index() {
        include 'index.php';
    }

    public function initialize() {
        include 'initialize.php';
    }

    public function channel() {
        include '';
    }

    public function logout() {
        include 'controller/signOutController.php';
    }

}