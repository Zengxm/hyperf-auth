<?php

declare(strict_types=1);
use Hyperf\Context\ApplicationContext;
use Qbhy\HyperfAuth\AuthGuard;
use Qbhy\HyperfAuth\AuthManager;
use Qbhy\HyperfAuth\Exception\GuardException;
use Qbhy\HyperfAuth\Exception\UserProviderException;

if (! function_exists('auth')) {
    /**
     * 建议视图中使用该函数，其他地方请使用注入.
     * @return AuthGuard|AuthManager|mixed
     * @throws UserProviderException
     * @throws GuardException
     */
    function auth(?string $guard = null): mixed
    {
        $auth = ApplicationContext::getContainer()->get(AuthManager::class);

        if (is_null($guard)) {
            return $auth;
        }

        return $auth->guard($guard);
    }
}

if (! function_exists('str_random')) {
    function str_random($num = 6): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $num; ++$i) {
            $index = rand(0, 61);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}

if (! function_exists('dev_clock')) {
    function dev_clock(string $title, callable $handler)
    {
        $start = microtime(true);
        $result = $handler();
        $end = microtime(true);
        dump($title . ' 用时：' . (($end - $start) * 1000) . 'ms');
        return $result;
    }
}
