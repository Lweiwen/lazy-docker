<?php

declare(strict_types=1);

namespace App\Model;



/**
 * @property int $id 
 * @property int $inviter_staff_id 
 * @property int $inviter_merchant_id 
 * @property int $inviter_service_provider_id 
 * @property int $promoter_parenter 
 * @property int $invite_promoter_num 
 * @property int $promoter_level 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Promoters extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'promoters';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'inviter_staff_id', 'inviter_merchant_id', 'inviter_service_provider_id', 'promoter_parenter', 'invite_promoter_num', 'promoter_level', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'inviter_staff_id' => 'integer', 'inviter_merchant_id' => 'integer', 'inviter_service_provider_id' => 'integer', 'promoter_parenter' => 'integer', 'invite_promoter_num' => 'integer', 'promoter_level' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
