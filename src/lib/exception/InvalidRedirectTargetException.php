<?php

namespace Framework\Exception;

use \Exception;

class InvalidRedirectTargetException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid redirect target! The redirect target must be in a form: controller/action');
    }
}
