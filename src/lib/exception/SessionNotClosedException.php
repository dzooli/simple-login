<?php

namespace Framework\Exception;

use \Exception;

class SessionNotClosedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Session cannot be closed");
    }
}
