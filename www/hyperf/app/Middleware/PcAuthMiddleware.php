<?php

namespace App\Middleware;

use App\Exception\ApiException;
use App\Services\AuthLogin\AuthGuard;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PcAuthMiddleware implements MiddlewareInterface
{

    public function __construct(protected AuthGuard $guard)
    {
    }

    /**
     * 验证
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @author LWW
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: Implement process() method.
        // 登录认证路径
        $loginPath = 'pc';
        $token = trim(ltrim($request->getHeaderLine('Authorization'), 'Bearer'));
        if (empty($token)) {
            throw new ApiException('登录token错误', 1001);
        }
        if (!$this->guard->checkAndSetAuthPath($token, $loginPath)) {
            throw new ApiException('登录路径错误', 1001);
        }
        // 从全局上下文中获取指定的角色
        if (!$this->guard->parseToken()) {
            throw new ApiException('请进行登录', 1001);
        }
        return $handler->handle($request);
    }
}