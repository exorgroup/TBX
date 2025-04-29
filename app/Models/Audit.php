<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use OwenIt\Auditing\Models\Audit as BaseAudit;

class Audit extends BaseAudit
{
    use CrudTrait;
    
    protected $table = 'audits';
    
    // Add any customizations you need here
    protected $fillable = [
        'user_type',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
        'SHASignature',
    ];
    
    /**
     * Get the user that the audit belongs to.
     */
    public function user()
    {
        $userModel = config('auth.providers.users.model');
        
        return $this->belongsTo(
            $userModel,
            'user_id'
        );
    }
}