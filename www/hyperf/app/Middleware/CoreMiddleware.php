<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CoreMiddleware extends \Hyperf\HttpServer\CoreMiddleware
{
    /**
     * Handle the response when cannot found any routes.
     * @param ServerRequestInterface $request
     * @return ResponseInterface|\Psr\Http\Message\MessageInterface
     * @author LWW
     */
    protected function handleNotFound(ServerRequestInterface $request
    ): ResponseInterface|\Psr\Http\Message\MessageInterface {
        // 重写路由找不到的处理逻辑
        return $this->response()->withStatus(404)->setBody(
            new SwooleStream('This is a way without a way')
        );
    }

    /**
     * Handle the response when the routes found but doesn't match any available methods.
     * @param array $methods
     * @param ServerRequestInterface $request
     * @return ResponseInterface|\Psr\Http\Message\MessageInterface
     * @author LWW
     */
    protected function handleMethodNotAllowed(
        array $methods,
        ServerRequestInterface $request
    ): ResponseInterface|\Psr\Http\Message\MessageInterface {
        // 重写 HTTP 方法不允许的处理逻辑
        return $this->response()->withStatus(405)->withBody(
            new SwooleStream('This is a way without a way')
        );
    }
}