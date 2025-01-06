<?php

declare(strict_types=1);

namespace Qbhy\HyperfAuth;

interface Authenticatable
{
    public function getId();

    public static function retrieveById($key): ?Authenticatable;
}
