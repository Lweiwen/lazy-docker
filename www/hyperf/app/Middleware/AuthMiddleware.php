<?php

namespace App\Middleware;

use App\Exception\ApiException;
use App\Model\ActivityRole;
use App\Model\Users;
use App\Model\UserBusinessRoles;
use App\Services\AuthLogin\AuthGuard;
use App\Services\AuthLogin\UserAuthenticate;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{

    /** @var RequestInterface */
    private RequestInterface $request;
    /** @var string 获取header中的token部分数据 */
    protected string $token = '';
    /** @var int 获取header中的商户类型 business_type部分数据 */
    protected int $businessType = 0;
    /** @var int 获取header中的具体商户id business_id部分数据 */
    protected int $businessId = 0;
    /** @var int 获取header中的获取服务商代管理的商家id manage_merchant_id部分数据 */
    protected int $manageMerchantId = 0;
    /** @var array 获取中间传参 */
    protected array $middlewareOptions = [];

    /**
     * @param AuthGuard $guard
     * @param RequestInterface $request
     */
    public function __construct(protected AuthGuard $guard, RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * 中间件逻辑重写
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
        /** 所有登录用户验证 */
        if (!$this->handleAuthorization()) {
            throw new ApiException('登录token错误');
        }
        //接收处理中间参数
        $this->dealMiddlewareOptions();

        if (!$this->handleLoginPath()) {
            throw new ApiException('登录路径错误');
        }
        if (!$this->handleUserVerification()) {
            throw new ApiException('请进行登录');
        }
        if (!$this->handleUserRole()) {
            throw new ApiException('Permission denied');
        }
        //处理路由传中间件参数
        return $handler->handle($request);
    }

    /**
     * 处理中间件参数
     * @return void
     * @author LWW
     */
    private function dealMiddlewareOptions(): void
    {
        $dispatched = $this->request->getAttribute(Dispatched::class);
        $this->middlewareOptions = $dispatched->handler->options;
    }

    /**
     * 解析Authorization与token
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @author LWW
     */
    private function handleAuthorization(): bool
    {
        //解析 Authorization
        $authorization = explode(',', trim(ltrim($this->request->getHeaderLine('Authorization'), 'Bearer')));
        $this->token = $authorization[0] ?? '';
        $this->businessType = $authorization[1] ?? CLIENT;
        $this->businessId = isset($authorization[2]) ? code_to_id_lj($authorization[2]) : 0;
        $this->manageMerchantId = isset($authorization[3]) ? code_to_id_lj($authorization[3]) : 0;
        return !empty($this->token);
    }

    /**
     * 判断登录路径（pc,web）
     * @return bool
     * @author LWW
     */
    private function handleLoginPath(): bool
    {
        $loginPath = $this->middlewareOptions['login_path'] ?? ['pc', 'web'];
        // 1.判断pc、web端
        return $this->guard->checkAndSetAuthPath($this->token, $loginPath);
    }

    /**
     * 验证用户信息
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @author LWW
     */
    private function handleUserVerification(): bool
    {
        [$userCode, $userId, $jwtLoginPath] = $this->guard->parseToken();
        return !empty($userCode) && !empty($userId) && $this->verifyUserInfo($userId, $userCode, $jwtLoginPath);
    }

    /**
     * 判断用户权限与用户角色
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @author LWW
     */
    private function handleUserRole(): bool
    {
        $userId = UserAuthenticate::id();
        $options = $this->middlewareOptions;
        $checkType = $options['check_type'] ?: '';
        $handlerMethodName = $options['handler'] ?? '';
        switch ($checkType) {
            case 'system':
                //用户类型操作权限判断
                // 3.判断中间件用户类型
                if (!$this->checkUserIdentify($options['user_identify'] ?? [])) {
                    return false;
                }
                // 4.如果是用户类型是商户，验证用户商户角色
                if (!$this->verifyBusinessInfo()) {
                    return false;
                }
                break;
            case 'activity':
                //活动操作权限判断 todo::暂时获取url中的活动id数据
                $activityCode = $this->request->route('activity_code') ?: '';
                // 活动身份处理
                if (!$this->verifyActivityInfo($userId, $activityCode)) {
                    return false;
                }
                break;
            default:
                return true;
                break;
        }
        return empty($handlerMethodName) || (method_exists($this, $handlerMethodName) && $this->{$handlerMethodName}());
    }

    /**
     * 判断中间件用户类型
     * 以下无需判断直接通过：
     * 1、中间件无需认证类型（userIdentify为空）
     * 2、当前Authorization以服务商类型进入
     * @param array $userIdentify
     * @return bool
     * @author LWW
     */
    private function checkUserIdentify(array $userIdentify): bool
    {
        $businessType = $this->businessType;
        return empty($userIdentify) || $businessType == SERVICE || in_array(
                CLIENT,
                $userIdentify
            ) || (!empty($businessType) && in_array(
                    $businessType,
                    $userIdentify
                ));
    }

    /**
     * 验证用户信息
     * @param int $userId
     * @param string $userCode
     * @param string $jwtLoginPath
     * @return bool
     * @author LWW
     */
    private function verifyUserInfo(int $userId, string $userCode, string $jwtLoginPath): bool
    {
        $obj = Users::findWithId($userId);
        if (!$obj) {
            return false;
        }
        $userInfo = [
            'uid'          => $obj->id,
            'user_code'    => $userCode,
            'password'     => $obj->password,
            'user_account' => $obj->account,
            'login_path'   => $jwtLoginPath
        ];
        return UserAuthenticate::setUserInfo($userInfo);
    }

    /**
     * 验证商户信息
     * @return bool
     * @author LWW
     */
    private function verifyBusinessInfo(): bool
    {
        $userId = UserAuthenticate::id();
        $businessType = $this->businessType;
        $businessId = $this->businessId;
        $manageMerchantId = $this->manageMerchantId;
        //普通用户角色则直接跳过
        if (empty($businessType) || $businessType == CLIENT) {
            return true;
        }
        if (empty($businessId)) {
            return false;
        }
        $objUserRole = UserBusinessRoles::findForUserRole($userId, $businessType, $businessId);
        if (!$objUserRole) {
            return false;
        }
        //todo::角色判断待定
        $staffInfo = [
            'business_id'        => $objUserRole->business_id,
            'business_type'      => $objUserRole->business_type,
            'name'               => $objUserRole->name,
            'status'             => $objUserRole->status ?? 0,
            'role'               => $objUserRole->role ?? 0,
            'manage_merchant_id' => $manageMerchantId,
        ];
        //储存员工信息
        UserAuthenticate::setStaffInfo($staffInfo);
        return true;
    }

    /**
     * 验证活动信息
     * @param int $userId
     * @param string $activityCode
     * @return true
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @author LWW
     */
    private function verifyActivityInfo(int $userId, string $activityCode): bool
    {
        $businessType = $this->businessType;
        $businessId = $this->businessId;
        $manageMerchantId = $this->manageMerchantId;
        //活动参数不足
        if (empty($activityCode) || empty($businessType) || empty($businessId)) {
            return false;
        }
        $activityId = code_to_id_lj($activityCode);
        if (empty($activityId)) {
            return false;
        }
        if ($businessType == SERVICE && !empty($manageMerchantId)) {
            //todo::代管理操作 查找是否授权操作
            $activityRoleData['role'] = A_ROLE_SPONSOR_SERVICE_MANAGER;
        } else {
            $objActRole = ActivityRole::findForActivityRole($activityId, $userId, $businessType, $businessId);
            if (!$objActRole) {
                return false;
            }
            $activityRoleData['role'] = $objActRole->role;
        }

        $activityRoleData['activity_id'] = $activityId;
        $activityRoleData['activity_code'] = $activityCode;
        $activityRoleData['business_type'] = $businessType;
        $activityRoleData['business_id'] = $businessId;
        $activityRoleData['mange_merchant'] = $manageMerchantId;
        UserAuthenticate::setActivityUserRole($activityRoleData);
        return true;
    }

    /**
     * 服务商创建人
     * @return bool
     * @author LWW
     */
    private function serviceProviderSupper(): bool
    {
        return UserAuthenticate::staffRoleNum() == S_ROLE_CREATOR;
    }

    /**
     * 服务商管理员与创建人
     * @return bool
     * @author LWW
     */
    private function serviceProviderForAllAdmin(): bool
    {
        return in_array(UserAuthenticate::staffRoleNum(), [S_ROLE_CREATOR, S_ROLE_ADMIN]);
    }

    /**
     * 商户（服务商+商家）创建者
     * @return bool
     * @author LWW
     */
    private function businessSupper(): bool
    {
        return in_array(UserAuthenticate::staffRoleNum(), [S_ROLE_CREATOR, M_ROLE_CREATOR]);
    }

    /**
     * 商家管理员+负责人
     * @return bool
     * @author LWW
     */
    private function merchantForAllAdmin(): bool
    {
        return in_array(UserAuthenticate::staffRoleNum(), [M_ROLE_CREATOR, M_ROLE_ADMIN]);
    }


    /******* 活动角色 *******/
    /**
     * 活动创建者
     * @author LWW
     */
    private function activityProviderSupper(): bool
    {
        return UserAuthenticate::activityRoleNum() == A_ROLE_SPONSOR_OWNER;
    }

}