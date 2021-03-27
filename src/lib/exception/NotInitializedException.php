<?php

namespace Framework\Exception;

use \Exception;

class NotInitializedException extends Exception
{
    public function __construct()
    {
        parent::__construct("The application is not initialized yet");
    }
}
