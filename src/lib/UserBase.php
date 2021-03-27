<?php

namespace Framework;

abstract class UserBase
{
    abstract public function getName(): string;
    abstract public function getId(): int;
    abstract public function authenticate(): bool;
    abstract protected function checkPassword(): bool;
    abstract public function getRoles(): array;
    abstract public function hasRoleByName(string $roneName): bool;
}
