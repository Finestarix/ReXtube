<?php

require_once(dirname(__FILE__) . '/../../util/uriHelper.php');

checkURI(realpath(__FILE__));

if (!function_exists('setSession')) {
    function setSession($user)
    {
        $_SESSION['ID'] = $user->id;
        $_SESSION['NAME'] = $user->name;
        $_SESSION['EMAIL'] = $user->email;
        $_SESSION['IMAGE'] = $user->image;
    }
}

if (!function_exists('getSession')) {
    function getSession()
    {
        if (!(isset($_SESSION['ID']) &&
            isset($_SESSION['NAME']) &&
            isset($_SESSION['EMAIL']) &&
            isset($_SESSION['IMAGE'])))
            return null;

        $user = new stdClass();
        $user->id =$_SESSION['ID'];
        $user->name =$_SESSION['NAME'];
        $user->email =$_SESSION['EMAIL'];
        $user->image =$_SESSION['IMAGE'];

        return $user;
    }
}