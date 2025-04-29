<?php

namespace App\Utils;

class SvgIcons
{
    /**
     * Get the check icon SVG for data integrity verification
     * 
     * @param string $color The stroke color
     * @param string $title The tooltip title
     * @return string HTML SVG element
     */
    public static function check($color = 'green', $title = 'Data is authentic')
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks" title="'.$title.'">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M7 12l5 5l10 -10" />
            <path d="M2 12l5 5m5 -5l5 -5" />
        </svg>';
    }
    
    /**
     * Get the x icon SVG for data integrity verification
     * 
     * @param string $color The stroke color
     * @param string $title The tooltip title
     * @return string HTML SVG element
     */
    public static function x($color = 'red', $title = 'Data has been altered')
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x" title="'.$title.'">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M18 6l-12 12" />
            <path d="M6 6l12 12" />
        </svg>';
    }
    
    /**
     * Get the appropriate integrity icon based on validation result
     * 
     * @param bool $isValid Whether the data is valid
     * @return string HTML SVG element
     */
    public static function integrityIcon($isValid)
    {
        return $isValid ? self::check() : self::x();
    }
}