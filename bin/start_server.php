<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PHPServer\Response;
use PHPServer\Request;
use PHPServer\Server;

$server = new Server('127.0.0.1', '5000');
$server->listen(function (Request $request) {
    return new Response('Welcome ' . $request->getParamsByKey('name') . '!');
});

