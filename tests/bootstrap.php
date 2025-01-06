<?php

declare(strict_types=1);
use Doctrine\Common\Cache\FilesystemCache;
use Hyperf\Cache\Driver\FileSystemDriver;
use Hyperf\Config\Config;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Container;
use Hyperf\Di\Definition\DefinitionSourceFactory;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Request;
use Hyperf\Redis\Redis;
use Hyperf\Session\Handler\FileHandler;
use Hyperf\Session\Session;
use Hyperf\Support\Filesystem\Filesystem;
use HyperfTest\DemoUser;
use Psr\SimpleCache\CacheInterface;
use Qbhy\HyperfAuth\AuthManager;
use Qbhy\HyperfAuth\Guard\JwtGuard;
use Qbhy\HyperfAuth\Guard\SessionGuard;
use Qbhy\HyperfAuth\Guard\SsoGuard;
use Qbhy\HyperfAuth\Provider\EloquentProvider;
use Qbhy\SimpleJwt\EncryptAdapters\CryptEncrypter;

use function Hyperf\Support\make;

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
define('BASE_PATH', $dir = dirname(__DIR__, 1));

$container = new Container((new DefinitionSourceFactory(false))());
ApplicationContext::setContainer($container);

$container->define(RequestInterface::class, function () {
    return new Request();
});

$container->define(CacheInterface::class, function () use ($container) {
    return new FileSystemDriver($container, []);
});

$container->define(SessionInterface::class, function () {
    return new Session('testing', new FileHandler(
        new Filesystem(),
        BASE_PATH . '/runtime/testing',
        10
    ));
});

$container->define(AuthManager::class, function () {
    $jwtConfig = [
        'driver' => JwtGuard::class, // guard 类名
        'secret' => 'test.secret',
        'provider' => 'test-provider', // 不设置的话用上面的 default.provider 或者用 'default'
        'encoder' => null,
        /*
         * 可选配置
         * 默认使用的加密类
         */
        'default' => CryptEncrypter::class,
        'cache' => new FilesystemCache(sys_get_temp_dir()), // 如果需要分布式部署，请选择 redis 或者其他支持分布式的缓存驱动
    ];

    return new AuthManager(new Config([
        'auth' => [
            'default' => [
                'guard' => 'jwt',
                'provider' => 'test-provider',
            ],

            'guards' => [
                'sso' => array_merge($jwtConfig, [
                    'driver' => SsoGuard::class,

                    // 支持的设备，用英文逗号隔开
                    'clients' => ['pc', 'weapp'],

                    // hyperf/redis 实例
                    'redis' => function () {
                        return make(Redis::class);
                    },

                    // 自定义 redis key，必须包含 {uid}，{uid} 会被替换成用户ID
                    'redis_key' => 'u:token:{uid}',
                    //                    'cache' => function () {
                    //                        return make(\Qbhy\HyperfAuth\HyperfRedisCache::class);
                    //                    },
                ]),
                'jwt' => $jwtConfig,
                'session' => [
                    'driver' => SessionGuard::class, // guard 类名
                    'provider' => 'test-provider', // 不设置的话用上面的 default.provider 或者用 'default'
                ],
            ],

            'providers' => [
                'test-provider' => [
                    'driver' => EloquentProvider::class, // user provider name
                    'model' => DemoUser::class,
                    // ... others config
                ],
            ],
        ],
    ]));
});
