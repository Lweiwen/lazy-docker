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

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use App\Exception\ApiException;
use App\Exception\BusinessException;
use App\Kernel\Http\Response;
use App\Kernel\Log\Log;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Exception\CircularDependencyException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\HttpException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function Hyperf\Support\env;

class BusinessExceptionHandler extends ExceptionHandler
{
    protected Response $response;

//    protected LoggerInterface $logger;

    public function __construct(protected ContainerInterface $container)
    {
        $this->response = $container->get(Response::class);
//        $this->logger = $container->get(StdoutLoggerInterface::class);
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $msg = '系统繁忙';
        $code = ErrorCode::SERVER_ERROR->value;
        switch (true) {
            case $throwable instanceof HttpException:
                return $this->response->handleException($throwable);
            case $throwable instanceof BusinessException:
//                $this->logger->warning(format_throwable($throwable));
                $code = $throwable->getCode();
                $msg = $throwable->getMessage();
                Log::get('error','error')->warning(format_throwable($throwable));

                break;
            case $throwable instanceof CircularDependencyException:
                $code = $throwable->getCode();
                $msg = $throwable->getMessage();
                Log::get('error','error')->error(format_throwable($throwable));

//                $this->logger->error($throwable->getMessage());
                break;
            case $throwable instanceof ApiException:
                $code = $throwable->getCode();
                $msg = $throwable->getMessage();
                break;
            default:
                Log::get('error','error')->error(format_throwable($throwable));

//                $this->logger->error(format_throwable($throwable));
                break;
        }
        $responseData = [];
        if (env('APP_ENV') === 'dev') {
            $responseData['debug'] = $this->getThrowableInfo($throwable);
        }

        return $this->response->fail($msg, $code, $responseData);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

    /**
     * 获取一个抛错对象的基本信息，用于调试api接口信息返回
     * @param Throwable $e
     * @return array
     * @author LWW
     */
    private function getThrowableInfo(Throwable $e): array
    {
        return [
            'throwable_type' => get_class($e),
            'message'        => $e->getMessage(),
            'code'           => $e->getCode(),
            'file'           => $e->getFile(),
            'line'           => $e->getLine(),
            'trace'          => $e->getTrace()
        ];
    }
}
