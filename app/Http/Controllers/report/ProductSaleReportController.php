<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProductSaleReportController extends Controller
{
    protected $converter;
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $saleProducts = '';
            $query = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->where('sales.status', 1);

            if ($request->product_id) {
                $query->where('sale_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {
                $query->where('sale_products.product_variant_id', $request->variant_id);
            }

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('sales.branch_id', NULL);
                } else {
                    $query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->customer_id) {
                if ($request->customer_id == 'NULL') {
                    $query->where('sales.customer_id', NULL);
                } else {
                    $query->where('sales.customer_id', $request->customer_id);
                }
            }

            if ($request->from_date) {
                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                // $date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('sales.report_date', $date_range);
            }

            $query->select(
                'sale_products.sale_id',
                'sale_products.product_id',
                'sale_products.product_variant_id',
                'sale_products.unit_price_inc_tax',
                'sale_products.quantity',
                'units.code_name as unit_code',
                'sale_products.subtotal',
                'sales.date',
                'sales.invoice_id',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'customers.name as customer_name'
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $saleProducts = $query->orderBy('sales.report_date', 'desc');
            } else {
                $saleProducts = $query->where('sales.branch_id', auth()->user()->branch_id)
                    ->orderBy('sales.report_date', 'desc');
            }

            return DataTables::of($saleProducts)
                ->editColumn('product', function ($row) {
                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                    return Str::limit($row->name, 25, '') . $variant;
                })->editColumn('sku', function ($row) {
                    return $row->variant_code ? $row->variant_code : $row->product_code;
                })->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })->editColumn('customer', function ($row) {
                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })->editColumn('quantity', function ($row) {
                    return $row->quantity . ' (<span class="qty" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>)';
                })->editColumn('unit_price_inc_tax', fn ($row) => '<span class="unit_price_inc_tax" data-value="' . $row->unit_price_inc_tax . '">' . $this->converter->format_in_bdt($row->unit_price_inc_tax) . '</span>')
                ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->subtotal . '">' . $this->converter->format_in_bdt($row->subtotal) . '</span>')
                ->rawColumns(['product', 'sku', 'date', 'quantity', 'branch', 'unit_price_inc_tax', 'subtotal'])->make(true);
        }
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.product_sale_report.index', compact('branches'));
    }

    // Product sale report print
    public function print(Request $request)
    {
        $saleProducts = '';
        $fromDate = '';
        $toDate = '';
        $branch_id = $request->branch_id;
        $query = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->where('sales.status', 1);

        if ($request->product_id) {
            $query->where('sale_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {
            $query->where('sale_products.product_variant_id', $request->variant_id);
        }

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('sales.branch_id', NULL);
            } else {
                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->customer_id) {
            if ($request->customer_id == 'NULL') {
                $query->where('sales.customer_id', NULL);
            } else {
                $query->where('sales.customer_id', $request->customer_id);
            }
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range);
        }

        $saleProducts = $query->select(
            'sale_products.sale_id',
            'sale_products.product_id',
            'sale_products.product_variant_id',
            'sale_products.unit_price_inc_tax',
            'sale_products.quantity',
            'units.code_name as unit_code',
            'sale_products.subtotal',
            'sales.date',
            'sales.report_date',
            'sales.invoice_id',
            'products.name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'customers.name as customer_name'
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $saleProducts = $query->orderBy('sales.report_date', 'desc')->get();
        } else {
            $saleProducts = $query->where('sales.branch_id', auth()->user()->branch_id)
                ->orderBy('sales.report_date', 'desc')->get();
        }

        return view('reports.product_sale_report.ajax_view.print', compact('saleProducts', 'fromDate', 'toDate', 'branch_id'));
    }

    // Search product
    public function searchProduct($product_name)
    {
        $products = DB::table('products')
            ->where('name', 'like', "%{$product_name}%")
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select(
                'products.id as product_id',
                'products.name',
                'products.product_code',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )
            ->get();

        if (count($products) > 0) {

            return view('reports.product_sale_report.ajax_view.search_result', compact('products'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }
}
