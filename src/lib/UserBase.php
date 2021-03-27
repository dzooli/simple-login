<?php

namespace Framework;

abstract class UserBase
{
    public function getName(): string;
    public function getId(): int;
    public function authenticate(): bool;
    protected function checkPassword(): bool;
    public function getRoles(): array;
    public function hasRoleByName(string $roneName): bool;
}
