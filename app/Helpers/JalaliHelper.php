<?php

namespace App\Helpers;

use DateTime;
use DateTimeZone;

class JalaliHelper
{
    /**
     * Convert Gregorian date to Jalali (Persian) date string.
     *
     * @param string|\DateTimeInterface|null $date
     * @param string $format
     * @return string
     */
    public static function toJalali($date, string $format = 'Y/m/d H:i'): string
    {
        if (empty($date)) {
            return '-';
        }

        try {
            if (!$date instanceof \DateTimeInterface) {
                $date = new DateTime($date);
            }

            $gYear = (int)$date->format('Y');
            $gMonth = (int)$date->format('m');
            $gDay = (int)$date->format('d');

            list($jYear, $jMonth, $jDay) = static::gregorianToJalali($gYear, $gMonth, $gDay);

            $hour = $date->format('H');
            $minute = $date->format('i');
            $second = $date->format('s');

            $jMonthPadded = sprintf('%02d', $jMonth);
            $jDayPadded = sprintf('%02d', $jDay);

            $result = str_replace(
                ['Y', 'm', 'd', 'H', 'i', 's'],
                [$jYear, $jMonthPadded, $jDayPadded, $hour, $minute, $second],
                $format
            );

            return static::toPersianDigits($result);
        } catch (\Throwable $e) {
            return (string)$date;
        }
    }

    /**
     * Convert English digits to Persian digits.
     */
    public static function toPersianDigits(string $string): string
    {
        $faDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($enDigits, $faDigits, $string);
    }

    /**
     * Convert Gregorian date numbers to Jalali date numbers.
     */
    public static function gregorianToJalali(int $g_y, int $g_m, int $g_d): array
    {
        $g_days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

        $gy = $g_y - 1600;
        $gm = $g_m - 1;
        $gd = $g_d - 1;

        $g_day_no = 365 * $gy + (int)(($gy + 3) / 4) - (int)(($gy + 99) / 100) + (int)(($gy + 399) / 400);

        for ($i = 0; $i < $gm; ++$i) {
            $g_day_no += $g_days_in_month[$i];
        }
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0))) {
            $g_day_no++;
        }
        $g_day_no += $gd;

        $j_day_no = $g_day_no - 79;

        $j_np = (int)($j_day_no / 12053);
        $j_day_no %= 12053;

        $jy = 979 + 33 * $j_np + 4 * (int)($j_day_no / 1461);
        $j_day_no %= 1461;

        if ($j_day_no >= 366) {
            $jy += (int)(($j_day_no - 1) / 365);
            $j_day_no = ($j_day_no - 1) % 365;
        }

        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) {
            $j_day_no -= $j_days_in_month[$i];
        }
        $jm = $i + 1;
        $jd = $j_day_no + 1;

        return [$jy, $jm, $jd];
    }
}
