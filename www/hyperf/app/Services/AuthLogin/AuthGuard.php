<?php

declare(strict_types=1);

namespace App\Services\AuthLogin;

use App\Services\BaseService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class AuthGuard
 * @package App\Services\AuthLogin
 * @property AuthService $authService
 */
class AuthGuard extends BaseService
{
    protected string $token;

    protected array|string $loginPath;

    /**
     * AuthGuard constructor.
     * @param AuthService $authService
     */
    public function __construct(private readonly AuthService $authService)
    {
    }

    /**
     * 设置登录途径
     * @param string $token
     * @param array|string $loginPath
     * @return bool
     * @author LWW
     */
    public function checkAndSetAuthPath(string $token, array|string $loginPath): bool
    {
        $this->token = $token;
        if (is_array($loginPath)) {
            return $this->autoSetLoginPath($loginPath);
        }
        $this->loginPath = $loginPath;
        return true;
    }

    /**
     * 获取token的登录路径
     * @param array $arrLoginPath
     * @return bool
     * @author LWW
     */
    public function autoSetLoginPath(array $arrLoginPath): bool
    {
        //获取token
        if (empty($this->token)) {
            return false;
        }
        /** 截取jwt载体内容，并解码 */
        $arrJwt = explode('.', $this->token);
        if (count($arrJwt) != 3 || empty($arrJwt[1])) {
            return false;
        }
        $payload = base64_decode($arrJwt[1], true);
        if ($payload) {
            $payload = json_decode($payload, true);
        }
        /** 如果能从payload获取有效的认证登录信息(path)，则设置，并返回true  */
        if (is_array($payload) && isset($payload['ctm'])) {
            $payload = json_decode($payload['ctm'], true);
        }
        if (
            is_array($payload)
            && isset($payload['path'])
            && (empty($arrLoginPath) || in_array($payload['path'], $arrLoginPath))
        ) {
            $this->loginPath = $payload['path'];
            return true;
        }
        return false;
    }

    /**
     * 解析token
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @author LWW
     */
    public function parseToken(): array
    {
        // 设置登录路径
        $this->authService->setSignatureKey($this->loginPath);
        //处理头部token
        $result = $this->authService->decryptAndParseForLogin($this->token, $this->loginPath);
        //解析用户code
        $userCode = $result['uid'];
        $userId = code_to_id_lj((string)$userCode);
        return [$userCode, $userId, $this->loginPath];
    }

}