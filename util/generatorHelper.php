<?php

require_once(dirname(__FILE__) . '/uriHelper.php');

checkURI(realpath(__FILE__));

if (!function_exists('generateUUID')) {
    function generateUUID()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

if (!function_exists('generateCSRF')) {
    function generateCSRF()
    {
        try {
            return bin2hex(random_bytes(64));
        } catch (Exception $e) {
        }
    }
}
