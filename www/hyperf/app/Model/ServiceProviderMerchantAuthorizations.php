<?php

declare(strict_types=1);

namespace App\Model;



/**
 * @property int $id 
 * @property int $service_provider_id 
 * @property int $merchant_id 
 * @property string $permissions 
 * @property \Carbon\Carbon $created_at 
 */
class ServiceProviderMerchantAuthorizations extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'service_provider_merchant_authorizations';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'service_provider_id', 'merchant_id', 'permissions', 'created_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'service_provider_id' => 'integer', 'merchant_id' => 'integer', 'created_at' => 'datetime'];
}
