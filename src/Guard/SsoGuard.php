<?php

declare(strict_types=1);
/**
 * This file is part of qbhy/hyperf-auth.
 *
 * @link     https://github.com/qbhy/hyperf-auth
 * @document https://github.com/qbhy/hyperf-auth/blob/master/README.md
 * @contact  qbhy0715@qq.com
 * @license  https://github.com/qbhy/hyperf-auth/blob/master/LICENSE
 */
namespace Qbhy\HyperfAuth\Guard;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Redis\Redis;
use Psr\EventDispatcher\EventDispatcherInterface;
use Qbhy\HyperfAuth\Authenticatable;
use Qbhy\HyperfAuth\Events\ForcedOfflineEvent;
use Qbhy\HyperfAuth\UserProvider;

class SsoGuard extends JwtGuard
{
    /**
     * @var Redis
     */
    protected $redis;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(array $config, string $name, UserProvider $userProvider, RequestInterface $request)
    {
        parent::__construct($config, $name, $userProvider, $request);

        // 初始化redis实例
        $this->redis = is_callable($config['redis']) ? call_user_func_array($config['redis'], []) : make(Redis::class);
    }

    public function getClients(): array
    {
        return $this->config['clients'] ?? ['unknown'];
    }

    public function login(Authenticatable $user, string $client = null)
    {
        $client = $client = $this->getClients()[0]; // 需要至少配置一个客户端
        $token = parent::login($user);
        $redisKey = str_replace('{uid}', (string) $user->getId(), $this->config['redis_key'] ?? 'u:token:{uid}');

        if (! empty($previousToken = $this->redis->hGet($redisKey, $client))) {
            // 如果存在上一个 token，就给他拉黑，也就是强制下线
            $this->getJwtManager()->addBlacklist($token);
            $this->redis->hDel($redisKey, $client);
            $this->eventDispatcher->dispatch(new ForcedOfflineEvent($user, $client));
        }

        $this->redis->hSet($redisKey, $client, $token);

        return $token;
    }
}
