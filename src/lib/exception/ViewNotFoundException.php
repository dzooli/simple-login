<?php

namespace Framework\Exception;

use \Exception;

class ViewNotFoundException extends Exception
{
    public function __construct(string $cname = '', string $vname = '')
    {
        parent::__construct("The specified view file: '" . $vname . "' for '" . $cname . "' does not exists");
    }
}
