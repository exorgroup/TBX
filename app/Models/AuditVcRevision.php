<?php

namespace App\Models;

use Venturecraft\Revisionable\Revision; // Import the base Revision model

class AuditVcRevision extends Revision
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'audit_vc'; // <-- Set your custom table name here

    // You can add custom methods or relationships here if needed in the future,
    // but for now, just setting the table name is sufficient.
}
