<?php

declare(strict_types=1);

namespace App\Model;


/**
 * @property int $id 
 * @property int $user_id 
 * @property int $business_id 
 * @property int $business_type 
 * @property int $role 
 * @property string $name 
 * @property int $login_time 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class UserBusinessRoles extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'user_business_roles';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'user_id', 'business_id', 'business_type', 'role', 'name', 'login_time', 'status', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'business_id' => 'integer', 'business_type' => 'integer', 'role' => 'integer', 'login_time' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 返回用户角色信息
     * @param int $userId
     * @param int $businessType
     * @param int $businessId
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     * @author LWW
     */
    public static function findForUserRole(int $userId, int $businessType, int $businessId)
    {
        return self::where(['user_id' => $userId, 'business_type' => $businessType, 'business_id' => $businessId])
            ->first();
    }
}
