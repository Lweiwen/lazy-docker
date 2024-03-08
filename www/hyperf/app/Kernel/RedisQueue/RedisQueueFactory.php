<?php

declare(strict_types=1);

namespace App\Kernel\RedisQueue;

use App\Job\AbstractJob;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\Context\ApplicationContext;

class RedisQueueFactory
{
    /**
     * 根据队列名称判断是否投递消息.
     */
    private const IS_PUSH_KEY = 'IS_PUSH_%s';

    /**
     * 获取队列实例
     * @param string $queueName
     * @return \Hyperf\AsyncQueue\Driver\DriverInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @author LWW
     */
    public static function getQueueInstance(string $queueName = 'default'): \Hyperf\AsyncQueue\Driver\DriverInterface
    {
        return ApplicationContext::getContainer()->get(DriverFactory::class)->get($queueName);
    }

    /**
     * 根据外部变量控制是否投递消息
     * @param AbstractJob $job
     * @param string $queueName
     * @param int $delay
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \RedisException
     * @author LWW
     */
    public static function safePush(AbstractJob $job, string $queueName = 'default', int $delay = 0): bool
    {
        // 动态读取外部变量, 判断是否投递
        $key = sprintf(static::IS_PUSH_KEY, $queueName);
        $isPush = ApplicationContext::getContainer()->get(\Hyperf\Redis\Redis::class)->get($key);
        if ($isPush){
            return self::getQueueInstance($queueName)->push($job, $delay);
        }
        return false;
    }
}