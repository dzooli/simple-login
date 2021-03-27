<?php

namespace Framework;

use Exception\NotInitializedException;

class Session 
{
    protected string $name;
    protected string $id;
    protected int $status;

    public function __construct() 
    {
        if (session_status() === PHP_SESSION_DISABLED) {
            throw new SessionDisabledExtension();
        }

        $this->name = $this->id = '';
        if (Myy::$app !== null && Myy::$app->hasValidParam('name')) {
            session_name(Myy::$app->getParams()['name']);
        } else {
            throw new NotInitializedException();
        }

        session_abort();
        session_reset();
        session_id(session_create_id(Myy::$app->getParams()['name']);
        session_start();

        $this->id = session_id();
        $this->name = session_name();
        $this->status = session_status();
    }

    public function __destruct()
    {
        session_write_close();
        session_destroy();
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getStatus() : int
    {
        return $this->status;
    }

    public function get(string $key = '') : string
    {
        if (!empty($key) && array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function remove(string $key) : bool
    {
        if (!empty($key) && array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
            return array_key_exists($key, $_SESSION);
        } else {
            return false;
        }        
    }

    public function has(string $key) : bool
    {
        if (empty($key)) {
            return false;
        }
        return array_key_exists($key, $_SESSION);
    }

    public function set(string $key, string $value) : bool
    {
        if (!empty($key)) {
                $_SESSION[$key] = $value;
        }
        return $this->has($key);
    }
}