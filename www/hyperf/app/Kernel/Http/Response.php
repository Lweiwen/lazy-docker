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

namespace App\Kernel\Http;

use Hyperf\Context\ResponseContext;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Swow\Psr7\Message\ResponsePlusInterface;

class Response
{
    public const OK = 1;

    public const ERROR = 0;

    protected ResponseInterface $response;

    public function __construct(protected ContainerInterface $container)
    {
        $this->response = $container->get(ResponseInterface::class);
    }

    public function success(
        string|array $msg = 'success',
        mixed $data = [],
        int $code = self::OK
    ): ResponsePlusInterface {
        if (is_array($msg)) {
            $data = $msg;
            $msg = 'success';
        }

        return $this->response
            ->json([
                       'code' => $code,
                       'msg'  => $msg,
                       'data' => $data,
                   ]);
    }

    public function fail(string|array $msg = '', int $code = self::ERROR, array $responseData = []): ResponsePlusInterface
    {
        if (!empty($responseData)){
            $responseArr = $responseData;
        }
        $responseArr['code'] = $code;
        $responseArr['msg'] = $msg;
        return $this->response
            ->json($responseArr);
    }

    public function redirect($url, int $status = 302): ResponsePlusInterface
    {
        return $this->response()
            ->setHeader('Location', (string)$url)
            ->setStatus($status);
    }

    public function cookie(Cookie $cookie)
    {
        ResponseContext::set($this->response()->withCookie($cookie));
        return $this;
    }

    public function handleException(HttpException $throwable): ResponsePlusInterface
    {
        if ($throwable instanceof BadRequestHttpException) {
            di()->get(StdoutLoggerInterface::class)->warning('body: ' . $throwable->getRequest()?->getBody() . ' ' . $throwable);
        }

        return $this->response()
            ->addHeader('Server', 'Hyperf')
            ->setStatus($throwable->getStatusCode())
            ->setBody(new SwooleStream($throwable->getMessage()));
    }

    public function response(): ResponsePlusInterface
    {
        return ResponseContext::get();
    }
}
