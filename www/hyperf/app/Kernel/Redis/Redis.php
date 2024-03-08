<?php

declare(strict_types=1);

namespace App\Kernel\Redis;

use Hyperf\Context\ApplicationContext;

class Redis
{
    /**
     * 获取Redis实例.
     * @return \Hyperf\Redis\Redis
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @author LWW
     */
    public static function getRedisInstance(): \Hyperf\Redis\Redis
    {
        return ApplicationContext::getContainer()->get(\Hyperf\Redis\Redis::class);
    }
}