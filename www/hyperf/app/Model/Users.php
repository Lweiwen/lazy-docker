<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id 
 * @property string $avatar
 * @property string $real_name 
 * @property string $nickname 
 * @property string $account 
 * @property string $password 
 * @property string $mobile 
 * @property string $openid 
 * @property int $is_bussiness 
 * @property int $is_promoter 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Users extends Model
{

    protected ?string $table = 'users';

    /**
     * 更具用户账号查找数据
     * @param string $account
     * @return object|null
     * @author LWW
     */
    public static function findWithAccount(string $account): object|null
    {
        return self::where('account', '=', $account)
            ->first();
    }

    /**
     * 根据用户id返回用户信息数据
     * @param int $id
     * @return object|null
     * @author LWW
     */
    public static function findWithId(int $id):object|null
    {
        return self::where('id', '=', $id)->first();
    }
}