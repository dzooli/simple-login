<?php

namespace Framework;

use Framework\Exception\NotInitializedException;
use Framework\Exception\SessionNotClosedException;
use Framework\Exception\SessionDisabledException;

class Session
{
    public static array $flashes = [
        'danger' => [
            'color' => 'red',
            'title' => 'Danger!',
        ],
        'warning' => [
            'color' => 'yellow',
            'title' => 'Warning!',
        ],
        'success' => [
            'color' => 'green',
            'title' => '',
        ],
        'info' => [
            'color' => 'blue',
            'title' => 'Info',
        ],
    ];

    protected string $name;
    protected string $sid;
    protected int $status;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_DISABLED) {
            throw new SessionDisabledException();
        }

        $this->name = $this->sid = '';
        if (Myy::$app === null) {
            throw new NotInitializedException();
        }

        $appName = Myy::$app->getName();
        session_name($appName);
        session_start(['cookie_lifetime' => 86400]);

        $this->sid = session_id();
        $this->name = session_name();
        $this->status = session_status();
    }

    public function getId(): string
    {
        return $this->sid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function get(string $key = ''): ?string
    {
        if (!empty($key) && array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function remove(string $key): bool
    {
        if (empty($key) || !array_key_exists($key, $_SESSION)) {
            return false;
        }
        unset($_SESSION[$key]);
        return array_key_exists($key, $_SESSION);
    }

    public function has(string $key): bool
    {
        if (empty($key)) {
            return false;
        }
        return array_key_exists($key, $_SESSION);
    }

    public function set(string $key, string $value): bool
    {
        if (!empty($key)) {
            $_SESSION[$key] = $value;
        }
        return $this->has($key);
    }

    public function close(): bool
    {
        foreach (array_keys($_SESSION) as $key) {
            $this->remove($key);
        }

        if (session_write_close()) {
            $this->name = '';
            $this->sid = '';
            $this->status = session_status();
            return true;
        }
        throw new SessionNotClosedException();
    }

    public static function setFlash(string $type = 'info', string $message = ''): bool
    {
        if (Myy::$app) {
            $sess = Myy::$app->getSession();
            if ($sess === null) {
                $sess = new Session();
                Myy::$app->setSession($sess);
            }
            $sess->set('flash', $type . '|' . $message);
            return Myy::$app->getSession()->has('flash');
        }
        return false;
    }

    public static function getFlash(): ?array
    {
        $retFlash = [];
        if (Myy::$app && Myy::$app->getSession() && Myy::$app->getSession()->has('flash')) {
            $flash = explode('|', Myy::$app->getSession()->get('flash'));
            Myy::$app->getSession()->remove('flash');
            $retFlash['color'] = self::$flashes['danger']['color'];
            $retFlash['title'] = self::$flashes['danger']['title'];
            $retFlash['msg'] = 'Internal error: Invalid flash message';
            if (count($flash) === 2 && in_array($flash[0], ['danger', 'warning', 'success', 'info'])) {
                $retFlash['msg'] = $flash[1] ?? '';
                $retFlash['color'] = self::$flashes[$flash[0]]['color'];
                $retFlash['title'] = self::$flashes[$flash[0]]['title'];
            }
            return $retFlash;
        }
        return null;
    }
}
