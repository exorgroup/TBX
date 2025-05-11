<?php

use App\Models\AuditVcRevision; // Import your custom Revision model

return [
    /*
    |--------------------------------------------------------------------------
    | Revision Model
    |--------------------------------------------------------------------------
    |
    | Define which Revision model implementation should be used.
    |
    */
    'model' => AuditVcRevision::class, // <-- Point this to your custom model

    'additional_fields' => [],

    // If you find you need other configuration options later,
    // you can add them here based on the full config file structure.
    // For example, if you need to explicitly set the table name here (though
    // the $table property on your model should take precedence):
    // 'table' => 'audit_vc',

];
