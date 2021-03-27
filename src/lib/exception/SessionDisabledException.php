<?php

namespace Framework\Exception;

use \Exception;

class SessionDisabledException extends Exception
{
    public function __construct()
    {
        parent::__construct("The PHP runtime environment has no session handling capabilities");
    }
}
