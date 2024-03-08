<?php

declare(strict_types=1);

namespace App\Model;



/**
 * @property int $id 
 * @property int $merchant_id 
 * @property int $status 
 * @property int $on_sale 
 * @property int $from_goods_id 
 * @property int $from_data_factory_id 
 * @property int $from_data_factory_goods_id 
 * @property int $sale_path_id 
 * @property int $sale_path 
 * @property int $sale_path_type 
 * @property int $factory_can_sync 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Goods extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'goods';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'merchant_id', 'status', 'on_sale', 'from_goods_id', 'from_data_factory_id', 'from_data_factory_goods_id', 'sale_path_id', 'sale_path', 'sale_path_type', 'factory_can_sync', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'merchant_id' => 'integer', 'status' => 'integer', 'on_sale' => 'integer', 'from_goods_id' => 'integer', 'from_data_factory_id' => 'integer', 'from_data_factory_goods_id' => 'integer', 'sale_path_id' => 'integer', 'sale_path' => 'integer', 'sale_path_type' => 'integer', 'factory_can_sync' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
