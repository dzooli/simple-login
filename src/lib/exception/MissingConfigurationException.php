<?php

namespace Framework\Exception;

use \Exception;

class MissingConfigurationException extends Exception
{
    public function __construct()
    {
        parent::__construct("Missing configuration detected");
    }
}
