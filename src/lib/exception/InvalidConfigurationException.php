<?php

namespace Framework\Exception;

use \Exception;

class InvalidConfigurationException extends Exception
{
    public function __construct()
    {
        parent::__construct("Invalid configuration detected");
    }
}
