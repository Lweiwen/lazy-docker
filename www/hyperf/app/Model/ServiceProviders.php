<?php

declare(strict_types=1);

namespace App\Model;



/**
 * @property int $id 
 * @property string $name 
 * @property string $logo_path 
 * @property int $user_id 
 * @property string $mobile
 * @property int $province_id 
 * @property int $city_id 
 * @property int $district_id 
 * @property string $detail_address 
 * @property int $apply_status 
 * @property string $reject_reason 
 * @property int $editor_status 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class ServiceProviders extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'service_providers';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'name', 'logo_path', 'user_id', 'contact', 'mobile', 'province_id', 'city_id', 'district_id', 'detail_address', 'apply_status', 'reject_reason', 'editor_status', 'status', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'province_id' => 'integer', 'city_id' => 'integer', 'district_id' => 'integer', 'apply_status' => 'integer', 'editor_status' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
