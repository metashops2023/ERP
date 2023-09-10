<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportPriceGroupProductController extends Controller
{
    public function export()
    {
        $priceGroups = DB::table('price_groups')->where('status', 'Active')->get();
        $products = DB::table('products')
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->select(
                'products.id as p_id',
                'products.product_code as p_code',
                'products.is_variant',
                'products.name',
                'products.product_price',
                'product_variants.id as v_id',
                'product_variants.variant_name',
                'product_variants.variant_price',
                'product_variants.variant_code as v_code',
                'taxes.tax_percent'
            )->orderBy('products.id', 'desc')->get();

        $export_data = [];
        foreach ($products as $product) {
            $temp = [];
            $__variant_name = $product->variant_name != null ? $product->variant_name : '';
            $__variant_id  = $product->v_id != null ? $product->v_id : NULL;
            $temp['Product'] = $product->name.' '.$__variant_name;
            $temp['Product Code(SKU)'] = $product->v_code != null ? (string)$product->v_code : (string)$product->p_code;
            $price = 0;
            $__tax = $product->tax_percent != null ? $product->tax_percent : 0;
            if ($product->is_variant == 1) {
                $price = $product->variant_price / 100 * $__tax + $product->variant_price;
            }else {
                $price = $product->product_price / 100 * $__tax + $product->product_price;
            }
            $temp['Base Price Inc.Tax'] = bcadd($price, 0, 2);
            foreach ($priceGroups as $pg) {
                $existsPrice = DB::table('price_group_products')->where('price_group_id', $pg->id)
                ->where('product_id', $product->p_id)->where('variant_id', $__variant_id)
                ->first(['price']);
                $temp[$pg->name] = $existsPrice && $existsPrice->price ? $existsPrice->price : '';
            }
            $export_data[] = $temp;
        }

        return collect($export_data)->downloadExcel(
            'product_price_groups.xlsx',
            null,
            true
        );
    }
}
