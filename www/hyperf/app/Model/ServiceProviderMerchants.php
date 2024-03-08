<?php

declare(strict_types=1);

namespace App\Model;



/**
 * @property int $id 
 * @property int $merchant_id 
 * @property int $service_provider_id 
 * @property int $staus 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class ServiceProviderMerchants extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'service_provider_merchants';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'merchant_id', 'service_provider_id', 'staus', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'merchant_id' => 'integer', 'service_provider_id' => 'integer', 'staus' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
