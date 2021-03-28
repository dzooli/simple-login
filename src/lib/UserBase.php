<?php

namespace Framework;

class UserBase
{
    protected int $id = 0;
    protected string $password = '';
    protected string $name = '';

    public function __construct(int $id = 0)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->username;
    }

    protected static function preCheck(): ?bool
    {
        if (empty(Myy::$app) || empty(Myy::$app->getDb())) {
            throw new NotInitializedException();
        }
        return true;
    }
}
