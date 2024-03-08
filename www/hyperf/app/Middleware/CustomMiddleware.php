<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpServer\Router\Dispatched;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class CustomMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Dispatched $dispatched */
        $dispatched = $request->getAttribute(Dispatched::class);
        $params = $dispatched->handler->options[static::class] ?? [];
        return $this->handle($request, $handler, ...$params);
    }

    abstract public function handle(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,...$params
    ): ResponseInterface;
}