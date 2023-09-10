<?php

/**
 * Converters helper methods
 */

use App\Models\Tax;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Warranty;
use App\Models\AdminAndUser;

$bn = ["১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০"];
$en = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

if(!function_exists('bn2en')) {
    function bn2en($number)
    {
        return str_replace($bn, $en, $number);
    }
}
if(!function_exists('en2bn')) {
    function en2bn($number)
    {
        return str_replace($en, $bn, $number);
    }
}
if(!function_exists('format_in_text')) {
    function format_in_text($number)
    {
        $fmt = new NumberFormatter( 'bn_BDT', NumberFormatter::SPELLOUT );
        // return \ucwords($fmt->format($number)); // Ten Million Two Thousand Three Hundred Forty-five Point Eight Nine
        return \ucfirst($fmt->format($number)); // Ten million two thousand three hundred forty-five point eight nine
    }
}
if(!function_exists('format_in_bdt')) {
    function format_in_bdt($number)
    {
        $fmt = new NumberFormatter( 'bn_BDT', NumberFormatter::DECIMAL );
        return bn2en($fmt->format($number));
    }
}
if(!function_exists('format_in_bdt_bn')) {
    function format_in_bdt_bn($number)
    {
        $fmt = new NumberFormatter( 'bn_BDT', NumberFormatter::DECIMAL );
        return en2bn($fmt->format($number));
    }
}

if(!function_exists('getUserCategories')) {
    function getUserCategories() {
        return Category::all();
    }
}

if(!function_exists('getUserBranches')) {
    function getUserBranches() {
        return Branch::all();
    }
}

if(!function_exists('getUserBrands')) {
    function getUserBrands() {
        return Brand::all();
    }
}

if(!function_exists('getUserUnits')) {
    function getUserUnits() {
        return Unit::all();
    }
}

if(!function_exists('getUserTaxes')) {
    function getUserTaxes() {
        return Tax::all();
    }
}

if(!function_exists('getUserWarranties')) {
    function getUserWarranties() {
        return Warranty::all();
    }
}
if(!function_exists('getBranchUser')) {
    function getBranchUser($bid) {
        return AdminAndUser::where('branch_id', $bid)->select('id')->first();
    }
}