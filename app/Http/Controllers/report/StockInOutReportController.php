<?php

namespace App\Http\Controllers\report;

use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class StockInOutReportController extends Controller
{
    public $converter;
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $stockInOuts = '';
            $query = DB::table('purchase_sale_product_chains')
                ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->leftJoin('productions', 'purchase_products.production_id', 'productions.id')

                ->leftJoin('product_opening_stocks', 'purchase_products.opening_stock_id', 'product_opening_stocks.id')
                ->leftJoin('sale_return_products', 'purchase_products.sale_return_product_id', 'sale_return_products.id')
                ->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                ->leftJoin('transfer_stock_branch_to_branch_products', 'purchase_products.transfer_branch_to_branch_product_id', 'transfer_stock_branch_to_branch_products.id')
                ->leftJoin('transfer_stock_branch_to_branches', 'transfer_stock_branch_to_branch_products.transfer_id', 'transfer_stock_branch_to_branches.id');

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
                $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
                $query->whereBetween('sales.report_date', $date_range);
            }

            $query->select(
                'sales.id as sale_id',
                'sales.date',
                'sales.invoice_id',
                'products.name',
                'products.created_at as product_created_at',
                'products.product_cost_with_tax as unit_cost_inc_tax',
                'product_variants.variant_name',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit',
                'purchase_sale_product_chains.sold_qty',
                'customers.name as customer_name',
                'branches.name as branch_name',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_inv',
                'productions.id as production_id',
                'productions.reference_no as production_voucher_no',
                'sale_returns.id as sale_return_id',
                'sale_returns.invoice_id as sale_return_invoice',
                'transfer_stock_branch_to_branches.id as transfer_id',
                'transfer_stock_branch_to_branches.ref_id as transfer_ref_id',
                'product_opening_stocks.id as pos_id',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity as stock_in_qty',
                'purchase_products.created_at as stock_in_date',
                'purchase_products.lot_no',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $stockInOuts = $query->orderBy('sales.report_date', 'desc');
            } else {

                $stockInOuts = $query->where('sales.branch_id', auth()->user()->branch_id)
                    ->orderBy('sales.report_date', 'desc');
            }

            return DataTables::of($stockInOuts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                    return Str::limit($row->name, 20, '') . $variant;
                })

                ->editColumn('sale', fn ($row) => '<a href="' . route('sales.show', [$row->sale_id]) . '" id="details" class="text-hover" title="view" >' . $row->invoice_id . '</a>')

                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })

                ->editColumn('branch',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name;
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'];
                    }
                })

                ->editColumn('unit_price_inc_tax', fn ($row) => '<span class="unit_price_inc_tax" data-value="' . $row->unit_price_inc_tax . '">' . $this->converter->format_in_bdt($row->unit_price_inc_tax) . '</span>')

                ->editColumn('sold_qty', function ($row) {

                    return '<span class="sold_qty" data-value="' . $row->sold_qty . '">' . $row->sold_qty . '/' . $row->unit . '</span>';
                })

                ->editColumn('customer_name', function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })

                ->editColumn('stock_in_by', function ($row) {

                    if ($row->purchase_inv) {

                        return 'Purchase: ' . '<a href="' . route('purchases.show', [$row->purchase_id]) . '" class="text-hover" id="details" title="view" >' . $row->purchase_inv . '</a>';
                    } else if ($row->production_voucher_no) {

                        return 'Production: ' . '<a href="' . route('manufacturing.productions.show', [$row->production_id]) . '" class=" text-hover" id="details" title="view" >' . $row->production_voucher_no . '</a>';
                    } else if ($row->pos_id) {

                        return 'Opening Stock';
                    } else if ($row->sale_return_id) {

                        return 'Sale Returned Stock : ' . '<a href="#" class="text-hover" id="details" title="view" >' . $row->sale_return_invoice . '</a>';
                    } else if ($row->transfer_id) {

                        return 'Received From Another B.L. : ' . '<a href="' . route('transfer.stock.branch.to.branch.receivable.show', [$row->transfer_id]) . '" class="text-hover" id="details" title="view" >' . $row->transfer_ref_id . '</a>';
                    } else {

                        return 'Non-Manageable-Stock';
                    }
                })

                ->editColumn('stock_in_date', function ($row) {
                    if ($row->stock_in_date) {

                        return date('d/m/Y', strtotime($row->stock_in_date));
                    } else {

                        return date('d/m/Y', strtotime($row->product_created_at));
                    }
                })

                // ->editColumn('stock_in_qty', function ($row) {
                //     return '<span class="stock_in_qty" data-value="' . $row->stock_in_qty . '">' . $row->stock_in_qty . '</span>';
                // })

                ->editColumn('net_unit_cost', function ($row) {

                    if ($row->net_unit_cost) {

                        return '<span class="net_unit_cost" data-value="' . $row->net_unit_cost . '">' . $row->net_unit_cost . '</span>';
                    } else {

                        return '<span class="net_unit_cost" data-value="' . $row->unit_cost_inc_tax . '">' . $row->unit_cost_inc_tax . '</span>';
                    }
                })

                ->rawColumns(
                    [
                        'product',
                        'sale',
                        'date',
                        'branch',
                        'unit_price_inc_tax',
                        'sold_qty',
                        'customer_name',
                        'stock_in_by',
                        'stock_in_date',
                        // 'stock_in_qty',
                        'net_unit_cost',
                    ]
                )->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->select('id', 'name')->get();

        return view('reports.stock_in_out_report.index', compact('branches', 'customers'));
    }

    public function print(Request $request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $branch_id = $request->branch_id;
        $stockInOuts = '';
        $fromDate = '';
        $toDate = '';

        $query = DB::table('purchase_sale_product_chains')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->leftJoin('productions', 'purchase_products.production_id', 'productions.id')
            ->leftJoin('product_opening_stocks', 'purchase_products.opening_stock_id', 'product_opening_stocks.id')
            ->leftJoin('sale_return_products', 'purchase_products.sale_return_product_id', 'sale_return_products.id')
            ->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id');

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
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range);
        }

        $query->select(
            'sales.id as sale_id',
            'sales.date',
            'sales.invoice_id',
            'products.name',
            'product_variants.variant_name',
            'sale_products.unit_price_inc_tax',
            'sale_products.unit',
            'purchase_sale_product_chains.sold_qty',
            'customers.name as customer_name',
            'branches.name as branch_name',
            'purchases.id as purchase_id',
            'purchases.invoice_id as purchase_inv',
            'productions.id as production_id',
            'productions.reference_no as production_voucher_no',
            'product_opening_stocks.id as pos_id',
            'purchase_products.net_unit_cost',
            'purchase_products.quantity as stock_in_qty',
            'purchase_products.created_at as stock_in_date',
            'sale_returns.id as sale_return_id',
            'sale_returns.invoice_id as sale_return_invoice',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $stockInOuts = $query->orderBy('sales.report_date', 'desc')->get();
        } else {

            $stockInOuts = $query->where('sales.branch_id', auth()->user()->branch_id)
                ->orderBy('sales.report_date', 'desc')->get();
        }

        return view('reports.stock_in_out_report.ajax_view.print', compact('stockInOuts', 'fromDate', 'toDate', 'branch_id'));
    }
}
