<?php

namespace App\Traits;

trait NormalizesPhoneNumber
{
    /**
     * Convert Eastern Arabic / Persian digits to Western ASCII digits.
     */
    public function convertDigitsToEnglish(string $string): string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $string = str_replace($persian, $english, $string);
        return str_replace($arabic, $english, $string);
    }

    /**
     * Normalize Iranian mobile numbers to standard 11-digit format: 09XXXXXXXXX
     */
    public function normalizePhoneNumber(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // 1. Convert Persian/Arabic digits to English
        $phone = $this->convertDigitsToEnglish($phone);

        // 2. Remove all non-numeric characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);

        // 3. Handle international prefixes
        if (str_starts_with($phone, '+98')) {
            $phone = '0' . substr($phone, 3);
        } elseif (str_starts_with($phone, '0098')) {
            $phone = '0' . substr($phone, 4);
        } elseif (str_starts_with($phone, '98') && strlen($phone) === 12) {
            $phone = '0' . substr($phone, 2);
        } elseif (strlen($phone) === 10 && str_starts_with($phone, '9')) {
            $phone = '0' . $phone;
        }

        return $phone;
    }
}
