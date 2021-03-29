<?php

namespace Framework;

use Framework\Myy;
use Framework\Exception\NotInitializedException;

class Model
{

    protected static function preCheck(): ?bool
    {
        if (empty(Myy::$app) || empty(Myy::$app->getDb())) {
            throw new NotInitializedException();
        }
        return true;
    }
}
