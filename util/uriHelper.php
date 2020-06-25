<?php

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

if (!function_exists('getAsset')) {
    function getAsset($assetPath) {

        $serverProtocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')  ? 'https' : 'http';
        $serverName = $_SERVER['SERVER_NAME'];
        $serverPort = $_SERVER['SERVER_PORT'];

        return sprintf('%s://%s:%s/%s', $serverProtocol, $serverName, $serverPort, $assetPath);
    }
}
