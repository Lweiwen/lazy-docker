<?php

declare(strict_types=1);

namespace App\Model;



/**
 * @property int $id 
 * @property int $service_provider_id 
 * @property string $edit_data 
 * @property int $submitter_id 
 * @property int $audit_time 
 * @property int $audit_status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class ServiceProviderEditApplications extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'service_provider_edit_applications';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'service_provider_id', 'edit_data', 'submitter_id', 'audit_time', 'audit_status', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'service_provider_id' => 'integer', 'submitter_id' => 'integer', 'audit_time' => 'integer', 'audit_status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
