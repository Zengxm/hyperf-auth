<?php

declare(strict_types=1);

namespace Qbhy\HyperfAuth\Provider;

use Qbhy\HyperfAuth\Authenticatable;

class EloquentProvider extends AbstractUserProvider
{
    public function retrieveByCredentials($credentials): ?Authenticatable
    {
        return call_user_func_array([$this->config['model'], 'retrieveById'], [$credentials]);
    }

    public function validateCredentials(Authenticatable $user, $credentials): bool
    {
        return $user->getId() === $credentials;
    }
}
