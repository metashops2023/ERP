<?php

namespace App\Http\Controllers\report;

use App\Charts\CommonChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SaleProduct;

class TrendingProductReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of supplier report
    public function index()
    {
        return view('reports.tranding_products.index');
    }

    // Index view of supplier report
    public function trandingProductList()
    {
        $labels = [];
        $values = [];
        $products = DB::table('products')->join('units', 'products.unit_id', 'units.id')->select('products.*', 'units.code_name')
        ->orderBy('number_of_sale', 'DESC')
        ->limit(5)->get();

        foreach ($products as $product) {
            $labels[] = $product->name. ' ('.$product->code_name.')';
            $values[] = (float) $product->number_of_sale;
        }

        $chart = new CommonChart();
        $chart->labels($labels)
            ->dataset('Total Sold Unit', 'column', $values);
        return view('reports.tranding_products.ajax_view.tranding_product_list', compact('chart'));
    }

    // public function trandingProductFilter(Request $request)
    // {
    //     return $topSoldProduct = DB::table('sale_products')
    //     ->select('product_id', DB::raw("SUM(QUANTITY) as quantity"))
    //     ->groupBy('sale_products.product_id')
    //     ->limit(5)->orderBy('QUANTITY', 'DESC')->distinct()->get();
    // }
}
