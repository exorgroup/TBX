<?php

namespace App\Services;

/**
 * Security class for data integrity and signature verification
 * 
 * This class provides methods to generate and verify SHA512 signatures
 * for database records, ensuring data integrity and detecting unauthorized 
 * modifications.
 */
class Security
{
    /**
     * Generate SHA512 signature for a record
     * 
     * Takes an array of values, concatenates them with a separator,
     * and returns a SHA512 hash as signature.
     * 
     * @param array $data Array of values to be protected
     * @return string SHA512 signature
     */
    public static function protectData(array $data): string
    {
        // Join all values with separator
        $concatenated = implode('|$$|', $data);
        
        // Return SHA512 hash
        return hash('sha512', $concatenated);
    }
    
    /**
     * Verify if the stored signature matches the calculated one
     * 
     * Regenerates the signature for the provided data and compares
     * it with the stored signature to detect data tampering.
     * 
     * @param array $data Array of values to verify
     * @param string $signature Stored signature to compare against
     * @return bool True if signature matches, false otherwise
     */
    public static function checkData(array $data, string $signature): bool
    {
        // Generate new signature
        $calculatedSignature = self::protectData($data);
        
        // Compare with provided signature using constant-time comparison
        // to prevent timing attacks
        return hash_equals($calculatedSignature, $signature);
    }
}