<?php

namespace Framework\Exception;

use \Exception;

class LoginAbortedException extends Exception
{
    public function __construct(string $msg = '')
    {
        parent::__construct('Login Aborted: ' . $msg);
    }
}
