<?php

namespace Framework\Exception;

use \Exception;

class InvalidRedirectParameterException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid redirect parameters! Empty array is not acceptible!');
    }
}
