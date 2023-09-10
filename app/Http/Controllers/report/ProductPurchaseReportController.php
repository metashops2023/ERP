<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProductPurchaseReportController extends Controller
{
    protected $converter;
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();
            $purchaseProducts = '';
            $query = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
                ->leftJoin('products', 'purchase_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
                ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
                ->leftJoin('units', 'products.unit_id', 'units.id');

            if ($request->product_id) {
                $query->where('purchase_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {
                $query->where('purchase_products.product_variant_id', $request->variant_id);
            }

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('purchases.branch_id', NULL);
                } else {
                    $query->where('purchases.branch_id', $request->branch_id);
                }
            }

            if ($request->supplier_id) {
                $query->where('purchases.supplier_id', $request->supplier_id);
            }

            if ($request->from_date) {
                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
                //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('purchases.report_date', $date_range);
            }

            $query->select(
                'purchase_products.purchase_id',
                'purchase_products.product_id',
                'purchase_products.product_variant_id',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity',
                'units.code_name as unit_code',
                'purchase_products.line_total',
                'purchase_products.selling_price',
                'purchases.date',
                'purchases.invoice_id',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'suppliers.name as supplier_name'
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $purchaseProducts = $query->where('purchases.is_purchased', 1)
                    ->orderBy('purchases.report_date', 'desc');
            } else {
                $purchaseProducts = $query->where('purchases.is_purchased', 1)
                    ->where('purchases.branch_id', auth()->user()->branch_id)
                    ->orderBy('purchases.report_date', 'desc');
            }

            return DataTables::of($purchaseProducts)
                ->editColumn('product', function ($row) {
                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                    return Str::limit($row->name, 25, '') . $variant;
                })
                ->editColumn('product_code', function ($row) {
                    return $row->variant_code ? $row->variant_code : $row->product_code;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('quantity', function ($row) {
                    return $row->quantity . ' (<span class="qty" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>)';
                })
                ->editColumn('net_unit_cost',  fn ($row) => '<span class="net_unit_cost" data-value="' . $row->net_unit_cost . '">' . $this->converter->format_in_bdt($row->net_unit_cost) . '</span>')
                ->editColumn('price',  function ($row) use ($converter) {
                    if ($row->selling_price > 0) {
                        return $converter->format_in_bdt($row->selling_price);
                    } else {
                        if ($row->variant_name) {
                            return $converter->format_in_bdt($row->variant_price);
                        } else {
                            return $converter->format_in_bdt($row->product_price);
                        }
                    }
                    return '<span class="net_unit_cost" data-value="' . $row->net_unit_cost . '">' . $converter->format_in_bdt($row->net_unit_cost) . '</span>';
                })
                ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->line_total . '">' . $this->converter->format_in_bdt($row->line_total) . '</span>')
                ->rawColumns(['product', 'product_code', 'date', 'quantity', 'branch', 'net_unit_cost', 'price', 'subtotal'])
                ->make(true);
        }
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone')->get();
        return view('reports.product_purchase_report.index', compact('branches', 'suppliers'));
    }

    public function print(Request $request)
    {
        $purchaseProducts = '';
        $fromDate = '';
        $toDate = '';
        $branch_id = $request->branch_id;
        $query = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id');

        if ($request->product_id) {
            $query->where('purchase_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {
            $query->where('purchase_products.product_variant_id', $request->variant_id);
        }

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('purchases.branch_id', NULL);
            } else {
                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_id) {
            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range);
        }

        $query->select(
            'purchase_products.purchase_id',
            'purchase_products.product_id',
            'purchase_products.product_variant_id',
            'purchase_products.net_unit_cost',
            'purchase_products.quantity',
            'units.code_name as unit_code',
            'purchase_products.line_total',
            'purchases.date',
            'purchases.report_date',
            'purchases.invoice_id',
            'products.name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'suppliers.name as supplier_name'
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $purchaseProducts = $query->orderBy('purchases.report_date', 'desc')->get();
        } else {
            $purchaseProducts = $query->where('purchases.branch_id', auth()->user()->branch_id)
                ->orderBy('purchases.report_date', 'desc')->get();
        }

        return view('reports.product_purchase_report.ajax_view.print', compact('purchaseProducts', 'fromDate', 'toDate', 'branch_id'));
    }
}
