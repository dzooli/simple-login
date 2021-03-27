<?php

$config = require_once(__DIR__ . '/../config/app.php');
require __DIR__ . '/../vendor/autoload.php';

use Framework\Application;
use Framework\Myy;
use Framework\Session;

//try {
$app = new Application($config) ?? null;
/*
} catch (\Throwable $th) {
    if (Application::hasValidParam($config, 'debug') && $config['params']['debug']) {
        (new Pages\Error(500, $th->getMessage()))->render();
    } else {
        header('HTTP/1.0 500 Internal Server Error');
    }
    exit(500);
}
*/
Myy::init();
Myy::$app = $app;
Myy::$user = null;

$app->setSession(new Session());

$app->getSession()->set('hello', 'world');
var_dump($_SESSION, $app->getSession()->getName(), $app->getSession()->getId(), SID);
echo "after shutdown \n";
$app->getSession()->close();
unset($app);
$app = null;
var_dump($_SESSION, SID);
