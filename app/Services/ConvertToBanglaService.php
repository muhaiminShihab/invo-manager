<?php

namespace App\Services;

class ConvertToBanglaService
{
    /**
     * Convert English numbers to Bangla numbers.
     */
    public static function number($number, $decimalPlaces = 0)
    {
        $number = number_format($number, $decimalPlaces);
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $banglaDigits  = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        return str_replace($englishDigits, $banglaDigits, $number);
    }
}