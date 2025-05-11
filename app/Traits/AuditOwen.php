<?php

namespace App\Traits;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as BaseAuditableTrait;

trait AuditOwen
{
    use BaseAuditableTrait;

    /**
     * Auditable attributes excluded from the Audit.
     *
     * @var array
     */
    protected $excludedAttributes = [
        //    'SHASignature', // Add the field name you want to exclude here
    ];

    // You can customize auditing options here if needed
    // For example, to specify which attributes to audit:
    // public function getAuditInclude(): array
    // {
    //     return ['attribute1', 'attribute2'];
    // }

    // Or to exclude attributes:
    // public function getAuditExclude(): array
    // {
    //     return ['created_at', 'updated_at'];
    // }

    // You can also override getAuditEvent() or modifyAttributeValue()
}
