<?php

declare(strict_types=1);

namespace Qbhy\HyperfAuth;

interface UserProvider
{
    /**
     * Retrieve a user by the given credentials.
     * @param mixed $credentials
     */
    public function retrieveByCredentials($credentials): ?Authenticatable;

    /**
     * Validate a user against the given credentials.
     * @param mixed $credentials
     */
    public function validateCredentials(Authenticatable $user, $credentials): bool;
}
