<?php

namespace App\Models;

use \Framework\UserBase;
use \Framework\Myy;

class User extends UserBase
{
    protected int $id = 0;
    protected string $username = '';
    protected string $email = '';
    protected string $password = '';
    protected $create_time = 0;
    protected $last_login = 0;
    protected bool $logged_in = false;

    public function __construct()
    {
        settype($this->id, 'integer');
        settype($this->logged_in, 'bool');
    }

    public static function findByEmail(string $email = ''): ?UserBase
    {
        if (empty($email)) {
            return null;
        }
        parent::preCheck();
        $dbconn = Myy::$app->getDb();
        $stmt = $dbconn->prepare('SELECT * FROM `user` WHERE email=:email_address');
        $dbDone = $stmt->execute([':email_address' => $email]);
        if ($dbDone) {
            $res = $stmt->fetchObject(__CLASS__);
        }
        return ($res instanceof UserBase) ? $res : null;
    }

    public static function findById(int $id = 0): ?UserBase
    {
        parent::preCheck();
        $dbconn = Myy::$app->getDb();
        $stmt = $dbconn->prepare('SELECT * FROM `user` WHERE id=:id');
        $dbDone = $stmt->execute([':id' => $id]);
        if ($dbDone) {
            $res = $stmt->fetchObject(__CLASS__);
        }
        return ($res instanceof UserBase) ? $res : null;
    }

    public function verifyPassword($pass = null): bool
    {
        if (!$pass || empty($this->password)) {
            return false;
        }
        return password_verify($pass, $this->password);
    }
}
