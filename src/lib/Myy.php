<?php

/**
 * PHP Version 7.4
 * @category Framework
 * @package Framework
 * @author Zoltan Fabian <email@email.com>
 * @license MIT
 */

namespace Framework;

use Framework\Application;
use Framework\Exception\NotInitializedException;
use Framework\Exception\LoginAbortedException;
use Framework\Session;
use Framework\UserBase;

/**
 * Framework main Object
 * 
 * Gives a frame to the application and handles the login/logout related tasks.
 */
class Myy
{

    /**
     * @property Framework\Application $app The main Application object
     */
    public static ?Application $app = null;

    /**
     * @property int $user_id   The logged-in user's id if any
     */
    public static int $user_id = 0;

    /**
     * Variable initialization
     * 
     * This is a reset function of the framework's internal state.
     * After calling this function, the Myy::$app variable should be
     * unavailable.
     *
     * @return void
     */
    public static function init(): void
    {
        self::$app = null;
    }

    /**
     * Is anybody logged in already?
     *
     * @return boolean True if self::$user_id is not equals zero
     */
    public static function isGuest(): bool
    {
        return (self::$user_id === 0);
    }

    /**
     * Creates the Application instance
     * 
     * This is the Framework init for the Application object. Also ussable for debugging since
     * it sets the max_execution_time INI variable to 5 minutes to avoid timeouts during debugging.
     * The INI setting is changed only upon Application->isDebugging() returns true.
     *
     * @param array|null $config    Application configuration array (see the example app's config/app.php)
     * @return void
     */
    public static function createApplication(?array $config): void
    {
        self::$app = new Application($config);
        if (self::$app->isDebugging()) {
            ini_set('max_execution_time', 300);
        }
    }

    /**
     * Login to the App framework
     * 
     * This static method initializes the in-app core variables required for user login.
     * It uses an existing Session or creates a new one and initializes the 'user_id' property
     * inside the session to the passed $user's 'id'.
     * Your User implementation has to be extended from UserBase with a properly implemented getId()
     * method.
     *
     * @param UserBase|null $user
     * @return void
     * 
     * @throws LoginAbortedException When empty user passed or getId() returns null
     * @throws NotInitializedException upon un-initialized $app static property (use self::createApplication() first)
     */
    public static function login(?UserBase $user = null)
    {
        if (empty(self::$app)) {
            throw new NotInitializedException();
        }

        if (empty($user) || $user->getId() === 0) {
            throw new LoginAbortedException('User not passed');
        }

        if (empty(Myy::$app->getSession())) {
            Myy::$app->setSession(new Session());
        }
        Myy::$app->getSession()->set('user_id', $user->getId());
        Myy::$user_id = $user->getId();
    }

    /**
     * Logout the current user
     * 
     * Logging out by closing the session and setting our self::$user_id to zero
     *
     * @return void
     */
    public static function logout()
    {
        if (!empty(Myy::$app) && !empty(Myy::$app->getSession())) {
            Myy::$app->getSession()->close();
        }
        Myy::$user_id = 0;
    }
}
