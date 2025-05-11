<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditOwen extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'audit_owen'; // Specify the custom table name

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id', // Changed from 'user_uuid' to 'user_id' to match standard ID
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at',
    ];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user that performed the audit.
     * This relationship is used by the Backpack CRUD controller.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // Use 'user_id' as the foreign key to match the 'id' in your users table.
        // \App\Models\User::class is correct based on your provided User model.
        return $this->belongsTo(\App\Models\User::class, 'user_id'); // <-- Changed to 'user_id'
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
