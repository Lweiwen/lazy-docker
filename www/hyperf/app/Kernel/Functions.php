<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Kernel\LJConvert\LJConvert;
use App\Services\Activity\ActivityBase;
use App\Services\AuthLogin\UserAuthenticate;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;
use Hyperf\Context\ApplicationContext;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;


if (!function_exists('di')) {
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @return mixed|\Psr\Container\ContainerInterface
     */
    function di(?string $id = null)
    {
        $container = ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }

        return $container;
    }
}

if (!function_exists('format_throwable')) {
    /**
     * Format a throwable to string.
     */
    function format_throwable(Throwable $throwable): string
    {
        return di()->get(FormatterInterface::class)->format($throwable);
    }
}

if (!function_exists('queue_push')) {
    /**
     * Push a job to async queue.
     */
    function queue_push(JobInterface $job, int $delay = 0, string $key = 'default'): bool
    {
        $driver = di()->get(DriverFactory::class)->get($key);
        return $driver->push($job, $delay);
    }
}

if (!function_exists('id_to_code_lj')) {
    /**
     * id转code工具快捷操作
     * @param int $id
     * @param int $length
     * @return mixed
     * @throws Exception
     * @author LWW
     */
    function id_to_code_lj(int $id, int $length = 6): mixed
    {
        try {
            return di()->get(LJConvert::class)->idToCode($id, $length);
        } catch (\Throwable $e) {
            throw new \App\Exception\ApiException($e->getMessage());
        }
    }
}


if (!function_exists('code_to_id_lj')) {
    /**
     * code转id工具快捷操作
     * @param string $code
     * @param int $length
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @author LWW
     */
    function code_to_id_lj(string $code, int $length = 6): mixed
    {
        try {
            return di()->get(LJConvert::class)->codeToId($code, $length);
        } catch (\Throwable $e) {
            throw new \App\Exception\ApiException($e->getMessage());
        }
    }
}

if (!function_exists('activity')) {
    /**
     * 返回activity信息
     * @return ActivityBase|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @author LWW
     */
    function activity(): mixed
    {
        return di()->get(ActivityBase::class);
    }
}
