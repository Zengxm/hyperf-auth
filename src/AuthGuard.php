<?php

declare(strict_types=1);

namespace Qbhy\HyperfAuth;

interface AuthGuard
{
    public function id();

    public function login(Authenticatable $user);

    public function user(): ?Authenticatable;

    public function check(): bool;

    public function guest(): bool;

    public function logout();

    public function getProvider(): UserProvider;

    public function getName(): string;
}
