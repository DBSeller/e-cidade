<?php
$requestUri = $_SERVER['REQUEST_URI'];

if (preg_match('/[^?]*api\/v\d\//', $requestUri)) {
    return require_once 'api/api.php';
}

if (preg_match('/.*v4\//', $requestUri)) {
    return require_once 'public/index.php';
}

if (preg_match('/rest\/v1\//', $requestUri)) {
    return require_once 'public/index.php';
}

return require('app.php');
