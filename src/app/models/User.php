<?php

namespace App\Models;

use \Framework\UserBase;
use \Framework\Myy;

/**
 * The Application specific User class
 * 
 * Could be adjusted to the specified requirements
 */
class User extends UserBase
{
    protected int $id = 0;
    protected string $username = '';
    protected string $email = '';
    protected string $password = '';
    protected $create_time = 0;
    protected $last_login = 0;
    protected bool $logged_in = false;

    /**
     * The constructor
     * 
     * In this application the constructor is only used to set the types of some properties 
     */
    public function __construct()
    {
        settype($this->id, 'integer');
        settype($this->logged_in, 'bool');
    }

    /**
     * Find the user by e-mail address
     *
     * @param string $email
     * @return UserBase|null    The first row as object instance from `user` table with the matching e-mail address
     */
    public static function findByEmail(string $email = ''): ?UserBase
    {
        if (empty($email)) {
            return null;
        }
        parent::preCheck();
        $dbconn = Myy::$app->getDb();
        $stmt = $dbconn->prepare('SELECT * FROM `user` WHERE email=:email_address ORDER BY id LIMIT 1');
        $dbDone = $stmt->execute([':email_address' => $email]);
        if ($dbDone) {
            $res = $stmt->fetchObject(__CLASS__);
        }
        return ($res instanceof UserBase) ? $res : null;
    }

    /**
     * Find the user by ID
     *
     * @param integer $id
     * @return UserBase|null    The user object with the specified ID or null when not found
     */
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

    /**
     * Verify the password
     * 
     * Compares the given $pass with the crypted hash in the DB.
     * You can generate passwords with password_hash(..., PASSWORD_DEFAULT) function.
     *
     * @param string $pass  The password to verify
     * @return boolean  True on match false otherwise
     */
    public function verifyPassword($pass = null): bool
    {
        if (!$pass || empty($this->password)) {
            return false;
        }
        return password_verify($pass, $this->password);
    }
}
