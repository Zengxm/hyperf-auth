<?php

declare(strict_types=1);

namespace Qbhy\HyperfAuth\Provider;

use Qbhy\HyperfAuth\UserProvider;

abstract class AbstractUserProvider implements UserProvider
{
    protected array $config;

    protected string $name;

    /**
     * AbstractUserProvider constructor.
     */
    public function __construct(array $config, string $name)
    {
        $this->config = $config;
        $this->name = $name;
    }
}
