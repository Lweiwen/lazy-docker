<?php

declare(strict_types=1);

namespace App\Model;



/**
 * @property int $id 
 * @property int $user_id 
 * @property int $log_type 
 * @property string $log_data 
 * @property \Carbon\Carbon $created_at 
 */
class ServiceProviderActionLogs extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'service_provider_action_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'user_id', 'log_type', 'log_data', 'created_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'log_type' => 'integer', 'created_at' => 'datetime'];
}
