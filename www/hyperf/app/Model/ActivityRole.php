<?php

declare(strict_types=1);

namespace App\Model;


/**
 * @property int $id
 * @property int $activity_id
 * @property int $user_id
 * @property int $business_type
 * @property int $business_id
 * @property int $role
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class ActivityRole extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'activity_role';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'id',
        'activity_id',
        'user_id',
        'business_type',
        'business_id',
        'role',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id'            => 'integer',
        'activity_id'   => 'integer',
        'user_id'       => 'integer',
        'business_type' => 'integer',
        'business_id'   => 'integer',
        'role'          => 'integer',
        'status'        => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];

    /**
     * 获取用户活动角色
     * @param int $activityId
     * @param int $userId
     * @param int $businessType
     * @param int $businessId
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     * @author LWW
     */
    public static function findForActivityRole(int $activityId, int $userId, int $businessType, int $businessId)
    {
        return self::where(
            [
                'activity_id'   => $activityId,
                'user_id'       => $userId,
                'business_type' => $businessType,
                'business_id'   => $businessId
            ]
        )->first();
    }
}
