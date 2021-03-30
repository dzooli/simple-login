<?php

namespace Framework;

use Framework\Myy;
use Framework\Exception\NotInitializedException;

/**
 * The base model
 */
class Model
{

    /**
     * Check for Application and DB connecttion
     *
     * @return boolean true if the application and the DB connection is exists
     * @throws NotInitializedException if one of them is missing
     */
    protected static function preCheck(): ?bool
    {
        if (empty(Myy::$app) || empty(Myy::$app->getDb())) {
            throw new NotInitializedException();
        }
        return true;
    }
}
