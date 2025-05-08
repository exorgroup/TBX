<?php

namespace App\Helpers;

class ValidationIconHelper
{
    /**
     * Get validation status icon as SVG
     * 
     * @param bool $isValid Whether the data is valid
     * @return string SVG icon HTML
     */
    public static function getValidationIcon(bool $isValid): string
    {
        // Handle null values
        if ($isValid === null) {
            $isValid = false;
        }

        if ($isValid) {
            // Green checkmark SVG
            return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#18BB29" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>';
        } else {
            // Red X SVG
            return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#A41212" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>';
        }
    }
}
