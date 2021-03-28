<?php

namespace Framework\Exception;

use \Exception;

class ControllerNotFoundException extends Exception
{
    public function __construct(string $cname = '')
    {
        parent::__construct("The specified Controller: '" . $cname . "' does not exists");
    }
}
