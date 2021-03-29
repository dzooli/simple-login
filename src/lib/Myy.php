<?php

namespace Framework;

use Framework\Application;
use Framework\Exception\NotInitializedException;
use Framework\Exception\LoginAbortedException;
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

    public static function login(?UserBase $user = null)
    {
        if (empty(self::$app)) {
            throw new NotInitializedException();
        }
        if (!empty($user) && $user->getId() > 0) {
            if (empty(Myy::$app->getSession())) {
                Myy::$app->setSession(new Session());
            }
            Myy::$app->getSession()->set('user_id', $user->getId());
            Myy::$user_id = $user->getId();
        } else {
            throw new LoginAbortedException('User not passed');
        }
    }

    public static function logout()
    {
        if (!empty(Myy::$app) && !empty(Myy::$app->getSession())) {
            Myy::$app->getSession()->close();
        }
        Myy::$user_id = 0;
    }
}
