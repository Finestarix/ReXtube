<?php
require_once(dirname(__FILE__) . '/../model/User.php');

if (!function_exists('setSession')) {
    function setSession($user)
    {
        $_SESSION['ID'] = $user->userID;
        $_SESSION['NAME'] = $user->userName;
        $_SESSION['EMAIL'] = $user->userEmail;
        $_SESSION['IMAGE'] = $user->userImage;
    }
}

if (!function_exists('getSession')) {
    function getSession()
    {
        if (!isset($_SESSION['ID']))
            return null;

        return new User(
            $_SESSION['ID'],
            $_SESSION['NAME'],
            $_SESSION['EMAIL'],
            $_SESSION['IMAGE']
        );
    }
}