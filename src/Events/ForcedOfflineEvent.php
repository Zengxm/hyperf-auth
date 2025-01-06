<?php

declare(strict_types=1);

namespace Qbhy\HyperfAuth\Events;

use Qbhy\HyperfAuth\Authenticatable;

/**
 * 被迫下线事件
 * Class ForcedOfflineEvent.
 */
class ForcedOfflineEvent
{
    /**
     * 用户实例.
     */
    public Authenticatable $user;

    /**
     * 客户端标识.
     */
    public string $client;

    /**
     * ForcedOfflineEvent constructor.
     */
    public function __construct(Authenticatable $user, string $client)
    {
        $this->user = $user;
        $this->client = $client;
    }
}
