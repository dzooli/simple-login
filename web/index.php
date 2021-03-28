<?php

$config = require_once(__DIR__ . '/../config/app.php');
require __DIR__ . '/../vendor/autoload.php';

use Framework\Myy;

Myy::init();

try {
    Myy::createApplication($config);
    echo Myy::$app->run()->getContent();
} catch (\Throwable $th) {
    if (Myy::$app->isDebugging()) {
        (new Pages\Error(500, $th->getMessage()))->render();
    } else {
        header('HTTP/1.0 500 Internal Server Error');
    }
    exit(500);
} finally {
    Myy::shutdownApplication();
}
