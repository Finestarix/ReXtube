<?php

checkURI(realpath(__FILE__));

function checkURI($fileLocation)
{
    if ($fileLocation == realpath($_SERVER['SCRIPT_FILENAME']))
        header('location: /');
}

if (!function_exists('getURI')) {
    function getURI()
    {
        $delimiterFullURI = '/';
        $delimiterObjectURI = '?';

        $fullURI = $_SERVER['REQUEST_URI'];
        $fullURIArr = explode($delimiterFullURI, $fullURI);

        $requestedURI = $fullURIArr[sizeof($fullURIArr) - 1];
        $requestedURIArr = explode($delimiterObjectURI, $requestedURI);

        return array(
            'path' => $requestedURIArr[0],
            'object' => (sizeof($requestedURIArr) > 1) ? $requestedURIArr[1] : ''
        );
    }
}


