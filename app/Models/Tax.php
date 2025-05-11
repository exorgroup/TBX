<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\Security;
use Illuminate\Support\Facades\Log;

use App\Traits\AuditOwen; // Import your custom trait
use OwenIt\Auditing\Contracts\Auditable; // Import the interface
use \Venturecraft\Revisionable\RevisionableTrait;



class Tax extends Model implements Auditable
{
    use CrudTrait;
    use SoftDeletes;
    use AuditOwen; // Use your custom trait
    use RevisionableTrait; // Use the Revisionable trait

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'taxes';
    protected $primaryKey = 'TaxID';
    protected $guarded = ['TaxID'];
    protected $fillable = ['TaxName', 'TaxRate', 'SHASignature'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'TaxRate' => 'decimal:2', // Cast to decimal with 2 decimal places
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Format TaxRate to always have 2 decimal places for signature generation
     */
    private function formatTaxRate($taxRate)
    {
        // Ensure consistent decimal format (2 decimal places)
        return number_format((float)$taxRate, 2, '.', '');
    }

    public function generateSignature()
    {
        $data = [
            $this->TaxName,
            $this->formatTaxRate($this->TaxRate),
        ];

        //    Log::info('1 Generate Signature Data: ', $data);

        return Security::protectData($data);
    }

    public function isValid()
    {
        // If signature is null, the record is invalid
        if (empty($this->SHASignature)) {
            return false;
        }
        $data = [
            $this->TaxName,
            $this->formatTaxRate($this->TaxRate),
        ];

        //    Log::info('2 Validating Signature Data: ', $data);
        //    Log::info('3 Stored Signature: ' . $this->SHASignature);

        return Security::checkData($data, $this->SHASignature);
    }

    /**
     * @return string
     */
    public function identifiableName()
    {
        return $this->name ?? $this->id; // Return an attribute that identifies the model
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Commented relationships to be used later
    /*
    public function events()
    {
        return $this->hasMany(Event::class, 'TaxID', 'TaxID');
    }

    public function stockItems()
    {
        return $this->hasMany(StockItem::class, 'TaxID', 'TaxID');
    }
    
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'TaxID', 'TaxID');
    }
    */

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

    protected static function boot()
    {
        parent::boot();

        // Debug model events
        static::creating(function ($model) {
            //    Log::info('4 Tax Model Creating Event');
            //    Log::info('5 TaxName: ' . $model->TaxName);
            //    Log::info('6 TaxRate: ' . $model->TaxRate);

            // Format TaxRate consistently for SHA generation
            $formattedTaxRate = $model->formatTaxRate($model->TaxRate);
            //    Log::info('6.1 Formatted TaxRate: ' . $formattedTaxRate);

            $data = [
                $model->TaxName,
                $formattedTaxRate,
            ];

            //    Log::info('7 Creating SHA Data: ', $data);
            $signature = Security::protectData($data);
            //    Log::info('8 Generated Signature: ' . $signature);

            $model->SHASignature = $signature;

            //    Log::info('9 Validating Signature Data: ', $data);
            //    Log::info('10 Check Signature: ' . Security::checkData($data, $signature));
            //    Log::info('11 Check Signature model: ' . Security::checkData($data, $model->SHASignature));
        });

        static::updating(function ($model) {
            //    Log::info('12 Tax Model Updating Event');
            //    Log::info('13 TaxName: ' . $model->TaxName);
            //    Log::info('14 TaxRate: ' . $model->TaxRate);

            // Format TaxRate consistently for SHA generation
            $formattedTaxRate = $model->formatTaxRate($model->TaxRate);
            //    Log::info('14.1 Formatted TaxRate: ' . $formattedTaxRate);

            $data = [
                $model->TaxName,
                $formattedTaxRate,
            ];

            //    Log::info('15 Updating SHA Data: ', $data);
            $signature = Security::protectData($data);
            //    Log::info('16 Generated Signature: ' . $signature);

            //    Log::info('17 Validating Signature Data: ', $data);
            //    Log::info('18 Check Signature: ' . Security::checkData($data, $signature));

            $model->SHASignature = $signature;
        });

        static::saving(function ($model) {
            //   Log::info('19 Tax Model Saving Event');
            //    Log::info('20 TaxName: ' . $model->TaxName);
            //    Log::info('21 TaxRate: ' . $model->TaxRate);
            //    Log::info('22 Current SHASignature: ' . $model->SHASignature);
        });

        static::saved(function ($model) {
            //    Log::info('23 Tax Model Saved Event');
            //    Log::info('24 Final SHASignature: ' . $model->SHASignature);
        });
    }
}
