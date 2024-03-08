<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\Context\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CoresMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: Implement process() method.
        $response = Context::get(ResponseInterface::class);
        $response = $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,OPTIONS')
            ->withHeader(
                'Access-Control-Allow-Headers',
                'Authorization, Origin, X-Requested-With, Content-Type, Accept'
            )
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
        Context::set(ResponseInterface::class, $response);
        if ($request->getMethod() == 'OPTIONS') {
            return $response;
        }
        return $handler->handle($request);
    }
}