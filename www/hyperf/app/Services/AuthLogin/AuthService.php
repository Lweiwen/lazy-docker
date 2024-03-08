<?php

declare(strict_types=1);

namespace App\Services\AuthLogin;

use App\Exception\ApiException;
use App\Services\BaseService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Hyperf\HttpServer\Contract\RequestInterface;

use function Hyperf\Support\env;
use function Hyperf\Support\make;

class AuthService extends BaseService
{
    /** @var string 加密密钥 */
    private static string $signatureKey = '';

    /** @var int 正常登陆的有效秒数 259200 72小时 */
    private static int $normalLoginLiveSecond = 259200;

    /** @var array|string[] 本地key */
    private static array $localKeys = [
        'PC' => 'ykxKrohQbpRNW0LqUgIsxA5aC0saGeG0p',
        'WEB' => 'F5BHA2ewmGvC1aB4oR1bdF3bBaB4oR',
    ];

    /**
     * 获取并设置密钥
     * @param string $path
     * @return mixed|string
     * @author LWW
     */
    public static function setSignatureKey(string $path): mixed
    {
        $path = strtoupper($path);
        if ((env('APP_ENV') == 'production')) {
            $key = env('JWT_KEY_' . $path, '');
        } else {
            $key = self::$localKeys[$path] ?? '';
        }
        if (empty($key)) {
            throw new ApiException('The JWT Key is empty!');
        }
        return self::$signatureKey = $key;
    }

    /**
     * 生成jwt Token
     * @param array $customerPayload
     * @param int $expireSeconds
     * @return string
     * @author LWW
     */
    public static function encrypt(array $customerPayload, int $expireSeconds = 0): string
    {
        $httpHost = di(RequestInterface::class)->getUri()->getHost();
        //签发人(不校验)
        $payload['iss'] = $httpHost;
        //受众(不校验)
        $payload['aud'] = $httpHost;
        //签发日期(会校验)
        $payload['iat'] = time();
        //有效日期(会校验)
        $payload['exp'] = time() + (empty($expireSeconds) ? self::$normalLoginLiveSecond : $expireSeconds);
        //随机种子(不校验)
        $payload['sed'] = md5(uniqid((string)microtime(true), true));
        //环境变量(会校验)
        $payload['rtm'] = env('APP_ENV');
        //自定义payload
        $payload['ctm'] = json_encode($customerPayload);
        return JWT::encode($payload, self::$signatureKey, 'HS256');
    }

    /**
     * 验证jwt是否正确
     * 根据加密方式，验证payload和签名部分是否匹配
     * @param string $token
     * @return object
     * @throws \Exception
     */
    public static function decrypt(string $token): object
    {
        if (empty($token)) {
            throw new \Exception('token为空');
        }
        if (empty(self::$signatureKey)) {
            throw new \Exception('No set JWT Key!');
        }
        try {
            return JWT::decode($token, new Key(self::$signatureKey, 'HS256'));
        } catch (\Firebase\JWT\ExpiredException $e) {
            throw new ApiException('Token has expired', 1001);
        } catch (\Firebase\JWT\BeforeValidException $e) {
            throw new ApiException('Invalid token', 1001);
        } catch (\Exception $e) {
            throw new ApiException('Please log in again', 1001);
        }
    }

    /**
     * 以登录方式，验证和解析token的内容
     * @param string $token
     * @param string $path
     * @return mixed
     * @author LWW
     */
    public static function decryptAndParseForLogin(string $token, string $path): mixed
    {
        //验证jwt的签名
        try {
            $objToken = self::decrypt($token);
        } catch (\Exception $e) {
            throw new ApiException('请重新登陆', 1001);
        }
        //验证token中的环境变量与当前是否一致
        if (empty($objToken->rtm) || $objToken->rtm != env('APP_ENV')) {
            throw new ApiException('非法入境登陆', 1001);
        }
        //验证token的签发时间有无大于当前时间
        if ($objToken->iat > time()) {
            throw new ApiException('无效token', 1001);
        }
        //验证token是否已经过期
        if ($objToken->exp < time()) {
            throw new ApiException('登录信息过期', 1001);
        }
        /** 获取并校验登录信息内容 */
        $customPayload = json_decode($objToken->ctm, true);
        //检验登录认证来源与传入的是否一致
        if (empty($customPayload['path']) || $customPayload['path'] != $path) {
            throw new ApiException('token来源不正确', 1001);
        }
        //是否有正确的uid信息
        if (empty($customPayload['uid'])) {
            throw new ApiException('无效登录信息', 1001);
        }
        //token剩余有效时间(秒)
        $customPayload['left_time'] = $objToken->exp - time();
        return $customPayload;
    }
}