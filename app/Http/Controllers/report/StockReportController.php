<?php

namespace App\Http\Controllers\report;

use App\Utils\Converter;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class StockReportController extends Controller
{
    protected $converter;
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    // Index view of Stock report
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();
            $branch_stock = '';
            $query = DB::table('product_branches')
                ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
                ->leftJoin('products', 'product_branches.product_id', 'products.id')
                ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
                ->leftJoin('branches', 'product_branches.branch_id', 'branches.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', 'brands.id')
                ->leftJoin('taxes', 'products.tax_id', 'taxes.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('product_branches.branch_id', NULL);
                } else {

                    $query->where('product_branches.branch_id', $request->branch_id);
                }
            }

            if ($request->category_id) {

                $query->where('products.category_id', $request->category_id);
            }

            if ($request->brand_id) {

                $query->where('products.brand_id', $request->brand_id);
            }

            if ($request->unit_id) {

                $query->where('products.unit_id', $request->unit_id);
            }

            if ($request->tax_id) {

                $query->where('products.tax_id', $request->tax_id);
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $branch_stock = $query;
            } else {

                $branch_stock = $query->where('product_branches.branch_id', auth()->user()->branch_id);
            }

            $query->select(
                'units.code_name',
                'branches.name as b_name',
                'branches.branch_code',
                'products.name',
                'products.product_code',
                'products.product_cost_with_tax',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_branches.product_quantity',
                'product_branches.total_sale',
                'product_branch_variants.variant_quantity',
                'product_branch_variants.total_sale as v_total_sale',
            );

            return DataTables::of($branch_stock)
                ->editColumn('product_code', fn ($row) => $row->variant_code ? $row->variant_code : $row->product_code)
                ->editColumn('name',  fn ($row) => Str::limit($row->name, 25, '') . ' ' . $row->variant_name)
                ->editColumn('branch',  function ($row) use ($generalSettings) {

                    if ($row->b_name) {

                        return $row->b_name . '/' . $row->branch_code . '(<b>BL</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                    }
                })
                ->editColumn('stock', fn ($row) => '<span class="stock" data-value="' . ($row->variant_quantity ? $row->variant_quantity : $row->product_quantity) . '">' . ($row->variant_quantity ? '<strong>' . $row->variant_quantity . '</strong>' : '<strong>' . $row->product_quantity . '</strong>') . '/' . $row->code_name . '</span>')
                ->editColumn('price',  fn ($row) => $row->variant_price ? $row->variant_price : $row->product_price)
                ->editColumn('stock_value',  function ($row) use ($converter) {
                    $price = $row->variant_cost_with_tax ? $row->variant_cost_with_tax : $row->product_cost_with_tax;
                    $stock = $row->variant_quantity ? $row->variant_quantity : $row->product_quantity;
                    $currentStockValue = $price * $stock;
                    return '<span class="stock_value" data-value="' . $currentStockValue . '">' . $converter->format_in_bdt($currentStockValue) . '</span>';
                })
                ->editColumn('total_sale', fn ($row) => '<span class="total_sale" data-value="' . ($row->v_total_sale ? $row->v_total_sale : $row->total_sale) . '">' . ($row->v_total_sale ? $row->v_total_sale : $row->total_sale) . '(' . $row->code_name . ')</span>')
                ->rawColumns(['product_code', 'name', 'branch', 'stock', 'price', 'stock_value', 'total_sale'])
                ->make(true);
        }
        $brands = DB::table('brands')->get(['id', 'name']);
        $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
        $taxes = DB::table('taxes')->get(['id', 'tax_name']);
        $units = DB::table('units')->get(['id', 'name']);
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.stock_report.index', compact('branches', 'brands', 'taxes', 'units', 'categories'));
    }

    // Get all product stock **requested by ajax**
    public function warehouseStock(Request $request)
    {
        if ($request->ajax()) {

            $converter = $this->converter;
            $generalSettings = DB::table('general_settings')->first();
            $warehouse_stock = '';
            $query = DB::table('product_warehouses')
                ->leftJoin('product_warehouse_variants', 'product_warehouses.id', 'product_warehouse_variants.product_warehouse_id')
                ->leftJoin('products', 'product_warehouses.product_id', 'products.id')
                ->leftJoin('product_variants', 'product_warehouse_variants.product_variant_id', 'product_variants.id')
                ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
                ->leftJoin('warehouse_branches', 'product_warehouses.warehouse_id', 'warehouse_branches.warehouse_id')
                ->leftJoin('branches', 'warehouse_branches.branch_id', 'branches.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', 'brands.id')
                ->leftJoin('taxes', 'products.tax_id', 'taxes.id');

            if ($request->warehouse_id) {

                $query->where('product_warehouses.warehouse_id', $request->warehouse_id);
            }

            if ($request->category_id) {

                $query->where('products.category_id', $request->category_id);
            }

            if ($request->brand_id) {

                $query->where('products.brand_id', $request->brand_id);
            }

            if ($request->unit_id) {

                $query->where('products.unit_id', $request->unit_id);
            }

            if ($request->tax_id) {

                $query->where('products.tax_id', $request->tax_id);
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $query;
            } else {

                if (empty($request->warehouse_id)) {

                    $query->where('warehouse_branches.branch_id', auth()->user()->branch_id);
                    $query->orWhere('warehouse_branches.is_global', 1);
                }
            }

            $warehouse_stock = $query->select(
                'units.code_name',
                'warehouses.warehouse_name as w_name',
                'warehouses.warehouse_code as w_code',
                'warehouse_branches.is_global',
                'branches.name as b_name',
                'branches.branch_code',
                'products.name',
                'products.product_code',
                'products.product_cost_with_tax',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_warehouses.product_quantity',
                'product_warehouse_variants.variant_quantity',
            );

            return DataTables::of($warehouse_stock)
                ->editColumn('product_code', fn ($row) => $row->variant_code ? $row->variant_code : $row->product_code)
                ->editColumn('name',  fn ($row) => Str::limit($row->name, 25, '') . ' ' . $row->variant_name)
                ->editColumn('branch',  function ($row) use ($generalSettings) {

                    if ($row->is_global == 1) {

                        return 'Global Access';
                    } else {

                        if ($row->b_name) {

                            return $row->b_name . '/' . $row->branch_code . '(<b>BL</b>)';
                        } else {

                            return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                        }
                    }
                })
                ->editColumn('warehouse', fn ($row) => $row->w_name . '/' . $row->w_code)
                ->editColumn('stock', fn ($row) => '<span class="stock" data-value="' . ($row->variant_quantity ? $row->variant_quantity : $row->product_quantity) . '">' . ($row->variant_quantity ? $row->variant_quantity : $row->product_quantity) . '(' . $row->code_name . ')</span>')
                ->editColumn('price',  fn ($row) => $row->variant_price ? $row->variant_price : $row->product_price)
                ->editColumn('stock_value',  function ($row) use ($converter) {
                    $price = $row->variant_cost_with_tax ? $row->variant_cost_with_tax : $row->product_cost_with_tax;
                    $stock = $row->variant_quantity ? $row->variant_quantity : $row->product_quantity;
                    $currentStockValue = $price * $stock;
                    return '<span class="stock_value" data-value="' . $currentStockValue . '">' . $converter->format_in_bdt($currentStockValue) . '</span>';
                })
                ->rawColumns(['product_code', 'name', 'branch', 'stock', 'price', 'stock_value',])
                ->make(true);
        }
    }

    // Print Branch Stock
    public function printBranchStock(Request $request)
    {
        $branch_id = $request->branch_id ? $request->branch_id : auth()->user()->branch_id;
        $branch_stock = '';
        $query = DB::table('product_branches')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->leftJoin('branches', 'product_branches.branch_id', 'branches.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('brands', 'products.brand_id', 'brands.id')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('product_branches.branch_id', NULL);
            } else {

                $query->where('product_branches.branch_id', $request->branch_id);
            }
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->unit_id) {

            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->tax_id) {

            $query->where('products.tax_id', $request->tax_id);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $query;
        } else {

             $query->where('product_branches.branch_id', auth()->user()->branch_id);
        }

        $branch_stock = $query->select(
            'units.code_name',
            'branches.name as b_name',
            'branches.branch_code',
            'products.name',
            'products.product_code',
            'products.product_cost_with_tax',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_cost_with_tax',
            'product_variants.variant_price',
            'product_branches.product_quantity',
            'product_branches.total_sale',
            'product_branch_variants.variant_quantity',
            'product_branch_variants.total_sale as v_total_sale',
        )->get();

        return view('reports.stock_report.ajax_view.branch_stock_print', compact('branch_stock', 'branch_id'));
    }
}
