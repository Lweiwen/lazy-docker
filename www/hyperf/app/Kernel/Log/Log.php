<?php

declare(strict_types=1);

namespace App\Kernel\Log;

use Hyperf\Logger\LoggerFactory as HyperfLoggerFactory;

class Log
{
    /**
     * 日志
     * @param string $name
     * @param string $group
     * @return \Psr\Log\LoggerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @author LWW
     */
    public static function get(string $name = 'app', string $group = 'default'): \Psr\Log\LoggerInterface
    {
        return di()->get(HyperfLoggerFactory::class)->make($name, $group);
    }
}