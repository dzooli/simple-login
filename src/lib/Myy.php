<?php

namespace Framework;

use Framework\Application;
use Framework\Session;
use Framework\UserBase;

class Myy
{

    public static ?Application $app;

    public static ?UserBase $user;

    public static function init(): void
    {
        self::$app = self::$user = null;
    }

    public static function isGuest(): bool
    {
        return (self::$user === null);
    }

    public static function createApplication(?array $config): void
    {
        self::$app = new Application($config);
        if (self::$app->isDebugging()) {
            ini_set('max_execution_time', 300);
        }
        self::$app->setSession(new Session());
    }

    public static function shutdownApplication(): void
    {
        self::$app->getSession()->close();
        self::$app = null;
    }
}
