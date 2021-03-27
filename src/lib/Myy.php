<?php

namespace Framework;

use Framework\Application;
use Framework\UserBase;

class Myy
{

    public static Application $app;

    public static UserBase $user;

    public static function init(): void
    {
        self::$app = self::$user = null;
    }
}
