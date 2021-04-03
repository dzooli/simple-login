<?php

namespace Framework\Exception;

use Exception;

class InternalErrorException extends Exception
{
    public function __construct(string $msg = '')
    {
        parent::__construct('Internal Error: ' . $msg);
    }
}
