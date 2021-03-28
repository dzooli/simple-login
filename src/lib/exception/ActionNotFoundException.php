<?php

namespace Framework\Exception;

use \Exception;

class ActionNotFoundException extends Exception
{
    public function __construct(string $aname = '', string $cname = '')
    {
        parent::__construct("The specified Action: '" . $aname . "' for '" . $cname . "' does not exists");
    }
}
