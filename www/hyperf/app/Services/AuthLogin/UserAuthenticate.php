<?php

namespace App\Services\AuthLogin;

use Hyperf\Context\Context;

class UserAuthenticate
{
    /** ---- 用户基本信息 ---- */

    /** @var string 存储用户信息key */
    const KEY_USER_INFO = 'user_info';
    /** @var string 存储当前用户角色信息key */
    const KEY_USER_ROLE = 'user_role';
    /** @var string 存储活动角色身份信息 */
    const KEY_USER_ACTIVITY_ROLE = 'activity_role';

    /**
     * 获取协程上下文信息
     * @param string $key
     * @param string|null $default
     * @return mixed|string|null
     * @author LWW
     */
    public static function getUserInfo(string $key = '', ?string $default = null): mixed
    {
        $userInfo = Context::get(self::KEY_USER_INFO, []);
        return !empty($key) ? $userInfo[$key] ?? $default : $userInfo;
    }

    /**
     * 设置用户信息
     * @param array $userInfo
     * @return bool
     * @author LWW
     */
    public static function setUserInfo(array $userInfo): bool
    {
        Context::set(self::KEY_USER_INFO, $userInfo);
        return true;
    }

    /**
     * 设置员工信息
     * @param array $staffInfo
     * @return bool
     * @author LWW
     */
    public static function setStaffInfo(array $staffInfo): bool
    {
        /** 设置员工信息 **/
        Context::set(self::KEY_USER_ROLE, $staffInfo);
        return self::checkStaffStatus();
    }

    /**
     * 获取用户角色身份信息
     * @param string $key
     * @param string|null $default
     * @return mixed
     * @author LWW
     */
    public static function getStaffInfo(string $key = '', ?string $default = null): mixed
    {
        $userRole = Context::get(self::KEY_USER_ROLE, []);
        return !empty($key) ? $userRole[$key] ?? $default : $userRole;
    }

    /**
     * 设置活动角色数据
     * @param array $activityData
     * @return bool
     * @author LWW
     */
    public static function setActivityUserRole(array $activityData): bool
    {
        /** 设置员工信息 **/
        Context::set(self::KEY_USER_ACTIVITY_ROLE, $activityData);
        return true;
    }

    /**
     * 返回活动角色
     * @param string $key
     * @param string|null $default
     * @return mixed|string|null
     * @author LWW
     */
    public static function getActivityUserRole(string $key = '', ?string $default = null): mixed
    {
        $userRole = Context::get(self::KEY_USER_ACTIVITY_ROLE, []);
        return !empty($key) ? $userRole[$key] ?? $default : $userRole;
    }

    /**
     * 获取用户的id
     *
     * @return int|null
     */
    public static function id(): ?int
    {
        return self::getUserInfo('uid');
    }

    /**
     * 获取用户的code
     * @return string
     * @author LWW
     */
    public static function userCode(): string
    {
        return self::getUserInfo('user_code', '');
    }

    /**
     * 获取员工名称
     * @return string
     * @author LWW
     */
    public static function staffName(): string
    {
        return self::getStaffInfo('name', '');
    }

    /**
     * 获取角色权限值
     * @return mixed|string|null
     * @author LWW
     */
    public static function staffRoleNum(): mixed
    {
        return self::getStaffInfo('role', '');
    }

    /**
     * 获取用户在对应端(路径)的账号状态
     * web路径不检查
     * staffStatus:0停用，1正常
     * @return bool
     * @author LWW
     */
    public static function checkStaffStatus(): bool
    {
        return self::getStaffInfo('status',false) ?: false;
    }

    /**
     * 获取活动角色
     * @return mixed|string|null
     * @author LWW
     */
    public static function activityRoleNum(): mixed
    {
        return self::getActivityUserRole('role', 0);
    }
}