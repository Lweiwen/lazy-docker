<?php

namespace App\Services\AuthLogin\Login;

use App\Exception\ApiException;
use App\Model\UserBusinessRoles;
use App\Model\Users;
use App\Services\AuthLogin\AuthService;


class PasswordLogin extends AuthService
{
    /**
     * 返回后台登录
     * @param string $account
     * @param string $password
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @author LWW
     */
    public function getBackstageLoginToken(string $account, string $password): array
    {
        $objUser = Users::findWithAccount($account);
        if (!$objUser || $objUser->status != 0 || !password_verify($password, $objUser->password)) {
            throw new ApiException('用户不存在');
        }
        $objBackstage = UserBusinessRoles::findForUserRole($objUser->id, 1,BACKSTAGE);
        if (!$objBackstage) {
            throw new ApiException('管理员不存在');
        }
        if ($objBackstage->status == 0) {
            throw new ApiException('该账号禁止登录');
        }
        //场景值
        $path = 'pc';
        //设置密钥
        self::setSignatureKey($path);
        $userCode = id_to_code_lj($objUser->id);
        $token = self::encrypt(
            [
                'uid'  => $userCode,
                'path' => $path
            ]
        );
//        UserBusinessRole::editLastLogin($objBackstage);
        //返回token和用户权限名称
        return [
            'user_id'      => $userCode,
            'login_token'  => $token,
            'name'         => $objBackstage->name,
            'user_account' => $account,
            'role'         => $objBackstage->role,
        ];
    }

    /**
     * 获取移动端登录token
     * @param string $account
     * @param string $password
     * @return array
     * @throws \Exception
     * @author LWW
     */
    public function getWebLoginToken(string $account, string $password): array
    {
        $objUser = Users::findWithAccount($account);
        if (!$objUser || $objUser->status != 0 || !password_verify($password, $objUser->password)) {
            throw new ApiException('用户不存在');
        }
        //场景值
        $path = 'web';
        //设置密钥
        self::setSignatureKey($path);
        $userCode = id_to_code_lj($objUser->id);
        $token = self::encrypt(
            [
                'uid'  => $userCode,
                'path' => $path
            ]
        );
        //返回token和用户权限名称
        return [
            'user_id'      => $userCode,
            'login_token'  => $token,
            'user_account' => $account,
        ];
    }
}