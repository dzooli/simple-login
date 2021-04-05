<?php

namespace Framework;

use Framework\Myy;
use Framework\Exception\NotInitializedException;

/**
 * The base model
 * 
 * This is the Model base for all database realted models. It is usable 
 * for extending to your domain specifice models regarding of the
 * application requirements.
 */
class Model
{

    /**
     * Check for Application initialization and DB connecttion
     *
     * @return boolean true if the application and the DB connection is exists
     * @throws NotInitializedException if one of the above is missing
     */
    protected static function preCheck(): ?bool
    {
        if (empty(Myy::$app) || empty(Myy::$app->getDb())) {
            throw new NotInitializedException();
        }
        return true;
    }
}
