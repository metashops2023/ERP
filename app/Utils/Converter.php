<?php

namespace App\Utils;

use NumberFormatter;

class Converter
{
    public static $bn = ["১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০"];
    public static $en = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

    public static function bn2en($number)
    {
        return str_replace(self::$bn, self::$en, $number);
    }

    public static function en2bn($number)
    {
        return str_replace(self::$en, self::$bn, $number);
    }

    /**
     * Format in BDT from floating numbers
     */

    public static function format_in_bdt($number)
    {
        return \number_format($number, 2, '.', ',');
        $fmt = new NumberFormatter( 'bn_BDT', NumberFormatter::DECIMAL );
        $fmt->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);
        $fmt->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);
        $fmt->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 2);
        return self::bn2en($fmt->format($number));
    }

    /**
     * Format Text format from floating numbers
     */

    public static function format_in_text($number)
    {
        $fmt = new NumberFormatter( 'bn_BDT', NumberFormatter::SPELLOUT );
        // return \ucwords($fmt->format($number)); // Ten Million Two Thousand Three Hundred Forty-five Point Eight Nine
        return \ucfirst($fmt->format($number)); // Ten million two thousand three hundred forty-five point eight nine
    }

    /**
     * Format in BDT (Bengali Language) from floating numbers
     */
    public static function format_in_bdt_bn($number)
    {
        $fmt = new NumberFormatter( 'bn_BDT', NumberFormatter::DECIMAL );
        $fmt->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);
        $fmt->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);
        $fmt->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 2);
        return self::en2bn($fmt->format($number));
    }
}

// echo BanglaEnglishConverter::format_in_text("10002345.89");
// echo PHP_EOL;
// echo BanglaEnglishConverter::format_in_bdt_bn("100000.89");

// echo BanglaEnglishConverter::format_in_bdt("10002345.89");
// echo PHP_EOL;
// echo BanglaEnglishConverter::format_in_bdt_bn("100000.89");

// echo PHP_EOL;

// $fmt = new NumberFormatter( 'bn_BDT', NumberFormatter::SPELLOUT );
// echo $fmt->format($num);

// echo PHP_EOL;
// $fmt = new NumberFormatter( 'en_BDT', NumberFormatter::DECIMAL );
// echo $fmt->format($num);


// echo PHP_EOL;
// $fmt = new NumberFormatter( 'BDT', NumberFormatter::DECIMAL );
// echo $fmt->format($num);

// $a = '১২'; //(12)
// $b = '৫'; //(5)

// $c = Converter::bn2en($a) + Converter::bn2en($b); // $c = 17
// echo Converter::en2bn($c); // ১৭
