<?php

namespace App\Models;

use Backpack\PermissionManager\app\Models\Permission as BackpackPermission;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Permission extends BackpackPermission implements Auditable
{
    use AuditableTrait;
    
    // Define which fields you want to audit
    protected $auditInclude = [
        'name', 
        'guard_name', 
        // Other attributes you want to track
    ];
}