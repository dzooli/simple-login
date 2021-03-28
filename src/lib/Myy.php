<?php

namespace Framework;

use Framework\Application;
use Framework\Session;
use Framework\UserBase;

class Myy
{

    public static ?Application $app = null;

    public static int $user_id = 0;

    public static function init(): void
    {
        self::$app = null;
    }

    public static function isGuest(): bool
    {
        return (self::$user_id === 0);
    }

    public static function createApplication(?array $config): void
    {
        self::$app = new Application($config);
        if (self::$app->isDebugging()) {
            ini_set('max_execution_time', 300);
        }
    }
}
