<?php

namespace App\Traits;

use App\Services\Security;
use App\Traits\HasDataSignature;
use OwenIt\Auditing\Auditable as AuditableTrait;

trait BaseModelTraits
{
    use HasDataSignature, AuditableTrait;
    
    /**
     * The attributes that should be added to all models
     */
    public static function bootBaseModelTraits()
    {
        static::creating(function ($model) {
            // Ensure SHA signature field is present
            if (!in_array('SHASignature', $model->fillable)) {
                $model->fillable[] = 'SHASignature';
            }
        });
    }
    
    /**
     * Get the audit model columns
     */
    public function getAuditableColumns()
    {
        // Get all columns except those we should exclude
        $excludeColumns = [
            'SHASignature', 'created_at', 'updated_at', 'deleted_at', 'remember_token'
        ];
        
        $columns = $this->getFillable();
        
        return array_diff($columns, $excludeColumns);
    }
    
    /**
     * Custom data integrity column for Backpack
     */
    public static function addDataIntegrityColumn()
    {
        return [
            'name' => 'data_integrity',
            'label' => 'Integrity',
            'type' => 'custom_html',
            'value' => function($entry) {
                return \App\Utils\SvgIcons::integrityIcon($entry->verifySignature());
            }
        ];
    }
}