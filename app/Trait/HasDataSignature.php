<?php

namespace App\Traits;

use App\Services\Security;

trait HasDataSignature
{
    /**
     * Generate SHA512 signature for the model
     * This should be called before saving the model
     */
    public function generateSignature()
    {
        // Get all attributes except the signature itself
        $attributes = $this->getAttributes();
        unset($attributes['SHASignature']);
        unset($attributes['created_at']);
        unset($attributes['updated_at']);
        unset($attributes['deleted_at']);
        
        // Generate and set the signature
        $this->SHASignature = Security::protectData($attributes);
        
        return $this;
    }
    
    /**
     * Verify if the current data matches the stored signature
     */
    public function verifySignature()
    {
        // Get all attributes except timestamps
        $attributes = $this->getAttributes();
        unset($attributes['SHASignature']);
        unset($attributes['created_at']);
        unset($attributes['updated_at']);
        unset($attributes['deleted_at']);
        
        return Security::checkData($attributes, $this->SHASignature);
    }
    
    /**
     * Boot the trait
     */
    protected static function bootHasDataSignature()
    {
        // Generate signature automatically before saving
        static::saving(function ($model) {
            $model->generateSignature();
        });
    }
}