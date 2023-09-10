<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Utils\Converter;
use App\Utils\AccountUtil;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\StockAdjustment;
use App\Utils\ProductStockUtil;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\StockAdjustmentProduct;
use App\Models\StockAdjustmentRecover;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\NameSearchUtil;
use App\Utils\UserActivityLogUtil;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentController extends Controller
{
    protected $productStockUtil;
    protected $converter;
    protected $invoiceVoucherRefIdUtil;
    protected $accountUtil;
    protected $nameSearchUtil;
    protected $userActivityLogUtil;

    public function __construct(
        ProductStockUtil $productStockUtil,
        Converter $converter,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        AccountUtil $accountUtil,
        NameSearchUtil $nameSearchUtil,
        UserActivityLogUtil $userActivityLogUtil
    ) {

        $this->productStockUtil = $productStockUtil;
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->accountUtil = $accountUtil;
        $this->nameSearchUtil = $nameSearchUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Index view of stock adjustment
    public function index(Request $request)
    {
        if (auth()->user()->permission->s_adjust['adjustment_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $adjustments = '';
            $query = DB::table('stock_adjustments')->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'stock_adjustments.warehouse_id', 'warehouses.id')
                ->leftJoin('admin_and_users', 'stock_adjustments.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('stock_adjustments.branch_id', NULL);
                } else {

                    $query->where('stock_adjustments.branch_id', $request->branch_id);
                }
            }

            if ($request->type) {

                $query->where('stock_adjustments.type', $request->type);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('stock_adjustments.report_date_ts', $date_range); // Final
            }

            $query->select(
                'stock_adjustments.*',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $adjustments = $query->orderBy('id', 'desc');
            } else {

                $adjustments = $query->where('stock_adjustments.branch_id', auth()->user()->branch_id)
                    ->orderBy('stock_adjustments.report_date_ts', 'desc');
            }

            return DataTables::of($adjustments)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a class="dropdown-item details_button" href="' . route('stock.adjustments.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> '.__("View").'</a>';

                        if (auth()->user()->permission->s_adjust['adjustment_delete'] == '1') {

                            if (auth()->user()->branch_id == $row->branch_id) {

                                $html .= '<a class="dropdown-item" id="delete" href="' . route('stock.adjustments.delete', $row->id) . '"><i class="far fa-trash-alt text-primary"></i> '.__("Delete").'
                                </a>';
                            }


                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('business_location',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('adjustment_location',  function ($row) use ($generalSettings) {

                    if ($row->warehouse_name) {

                        return $row->warehouse_name . '/' . $row->warehouse_code . '(<b>WH</b>)';
                    } elseif ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('type',  function ($row) {

                    return $row->type == 1 ? '<span class="badge bg-primary">Normal</span>' : '<span class="badge bg-danger">Abnormal</span>';
                })

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . $this->converter->format_in_bdt($row->net_total_amount) . '</span>')

                ->editColumn('recovered_amount', fn ($row) => '<span class="recovered_amount" data-value="' . $row->recovered_amount . '">' . $this->converter->format_in_bdt($row->recovered_amount) . '</span>')

                ->editColumn('created_by', fn ($row) => $row->prefix . ' ' . $row->name . ' ' . $row->last_name)

                ->rawColumns(['action', 'date', 'invoice_id', 'business_location', 'adjustment_location', 'type', 'net_total_amount', 'recovered_amount', 'created_by'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('stock_adjustment.index', compact('branches'));
    }

    public function show($adjustmentId)
    {
        $adjustment = StockAdjustment::with(
            'warehouse',
            'branch',
            'adjustment_products',
            'adjustment_products.product',
            'adjustment_products.variant',
            'admin',
            'recover',
            'recover.paymentMethod',
            'recover.account',
        )->where('id', $adjustmentId)->first();

        return view('stock_adjustment.ajax_view.show', compact('adjustment'));
    }

    // Stock adjustment create view
    public function create()
    {
        if (auth()->user()->permission->s_adjust['adjustment_add_from_location'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $stockAdjustmentAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 22)
            ->get(['accounts.id', 'accounts.name']);

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('stock_adjustment.create', compact('stockAdjustmentAccounts', 'accounts', 'methods'));
    }

    public function createFromWarehouse()
    {
        if (auth()->user()->permission->s_adjust['adjustment_add_from_warehouse'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $stockAdjustmentAccounts = DB::table('accounts')
            ->where('accounts.branch_id', auth()->user()->branch_id)
            ->where('account_type', 22)
            ->get(['id', 'name']);

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('stock_adjustment.create_from_warehouse', compact('warehouses', 'stockAdjustmentAccounts', 'accounts', 'methods'));
    }

    // Store Stock Adjustment
    public function store(Request $request)
    {
        if (isset($request->warehouse_count)) {

            if (auth()->user()->permission->s_adjust['adjustment_add_from_warehouse'] == '0') {

                return response()->json('Access Denied.');
            }
        } else {

            if (auth()->user()->permission->s_adjust['adjustment_add_from_location'] == '0') {

                return response()->json('Access Denied.');
            }
        }

        $this->validate($request, [
            'date' => 'required',
            'type' => 'required',
            'adjustment_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'adjustment_account_id.required' => 'Adjustment A/C is required.',
            'account_id.required' => 'Debit A/C is required.'
        ]);

        if (isset($request->warehouse_count)) {

            $this->validate($request, [
                'warehouse_id' => 'required',
            ]);
        }

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'product table is empty.']);
        }

        $settings = DB::table('general_settings')
            ->select(['prefix'])
            ->first();

        $voucherPrefix = json_decode($settings->prefix, true)['stock_djustment'];
        $__voucherPrefix = $voucherPrefix != null ? $voucherPrefix : '';

        // generate invoice ID
        $invoiceId = $__voucherPrefix . str_pad($this->invoiceVoucherRefIdUtil->getLastId('stock_adjustments'), 5, "0", STR_PAD_LEFT);

        // Add Stock adjustment.
        $addStockAdjustment = new StockAdjustment();
        $addStockAdjustment->warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : NULL;
        $addStockAdjustment->branch_id = auth()->user()->branch_id;

        $addStockAdjustment->invoice_id = $request->invoice_id ? $request->invoice_id : $invoiceId;
        $addStockAdjustment->stock_adjustment_account_id = $request->adjustment_account_id;
        $addStockAdjustment->type = $request->type;
        $addStockAdjustment->total_item = $request->total_item;
        $addStockAdjustment->net_total_amount = $request->net_total_amount;
        $addStockAdjustment->recovered_amount = $request->total_recovered_amount ? $request->total_recovered_amount : 0;
        $addStockAdjustment->date = $request->date;
        $addStockAdjustment->time = date('h:i:s a');;
        $addStockAdjustment->month = date('F');
        $addStockAdjustment->year = date('Y');
        $addStockAdjustment->report_date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addStockAdjustment->admin_id = auth()->user()->id;
        $addStockAdjustment->reason = $request->reason;
        $addStockAdjustment->save();

        if (isset($request->warehouse_count)) {

            $this->userActivityLogUtil->addLog(
                action: 1,
                subject_type: 14,
                data_obj: $addStockAdjustment
            );
        } else {

            $this->userActivityLogUtil->addLog(
                action: 1,
                subject_type: 13,
                data_obj: $addStockAdjustment
            );
        }

        // Add Purchase A/C Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 7,
            date: $request->date,
            account_id: $request->adjustment_account_id,
            trans_id: $addStockAdjustment->id,
            amount: $request->net_total_amount,
            balance_type: 'credit'
        );

        $index = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;
            $addStockAdjustmentProduct = new StockAdjustmentProduct();
            $addStockAdjustmentProduct->stock_adjustment_id = $addStockAdjustment->id;
            $addStockAdjustmentProduct->product_id = $product_id;
            $addStockAdjustmentProduct->product_variant_id = $variant_id;
            $addStockAdjustmentProduct->quantity = $request->quantities[$index];
            $addStockAdjustmentProduct->unit = $request->units[$index];
            $addStockAdjustmentProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
            $addStockAdjustmentProduct->subtotal = $request->subtotals[$index];
            $addStockAdjustmentProduct->save();

            $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);

            if (isset($request->warehouse_id)) {

                $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $request->warehouse_id);
            } else {

                $this->productStockUtil->adjustBranchStock($product_id, $variant_id, auth()->user()->branch_id);
            }
            $index++;
        }

        if ($request->total_recovered_amount > 0) {

            $voucher_no = str_pad($this->invoiceVoucherRefIdUtil->getLastId('stock_adjustment_recovers'), 5, "0", STR_PAD_LEFT);
            $addStockAdjustmentRecovered = new StockAdjustmentRecover();
            $addStockAdjustmentRecovered->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
            $addStockAdjustmentRecovered->voucher_no = 'SARV' . $voucher_no;
            $addStockAdjustmentRecovered->stock_adjustment_id = $addStockAdjustment->id;
            $addStockAdjustmentRecovered->account_id = $request->account_id;
            $addStockAdjustmentRecovered->payment_method_id = $request->payment_method_id;
            $addStockAdjustmentRecovered->recovered_amount = $request->total_recovered_amount;
            $addStockAdjustmentRecovered->save();

            // Add Purchase A/C Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 8,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $addStockAdjustmentRecovered->id,
                amount: $request->total_recovered_amount,
                balance_type: 'debit'
            );
        }

        session()->flash('successMsg', 'Stock adjustment created successfully');

            return response()->json(__('Stock adjustment created successfully'));
        

    }

    // Delete stock adjustment
    public function delete($adjustmentId)
    {
        $deleteAdjustment = StockAdjustment::with([
            'adjustment_products',
            'adjustment_products.product',
            'adjustment_products.variant',
            'recover',
        ])->where('id', $adjustmentId)->first();

        if (!is_null($deleteAdjustment)) {

            $storedWarehouseId = $deleteAdjustment->warehouse_id;
            $storedBranchId = $deleteAdjustment->branch_id;
            $storedStockAdjustmentAccountId = $deleteAdjustment->stock_adjustment_account_id;
            $storedAccountId = $deleteAdjustment->recover ? $deleteAdjustment->recover->account_id : NULL;
            $storedAdjustmentProducts = $deleteAdjustment->adjustment_products;

            if (isset($storedWarehouseId)) {

                $this->userActivityLogUtil->addLog(
                    action: 3,
                    subject_type: 14,
                    data_obj: $deleteAdjustment
                );
            } else {

                $this->userActivityLogUtil->addLog(
                    action: 3,
                    subject_type: 13,
                    data_obj: $deleteAdjustment
                );
            }

            $deleteAdjustment->delete();

            foreach ($storedAdjustmentProducts as $adjustment_product) {

                // Update product qty for adjustment
                $this->productStockUtil->adjustMainProductAndVariantStock($adjustment_product->product_id, $adjustment_product->product_variant_id);

                if ($storedWarehouseId) {

                    $this->productStockUtil->adjustWarehouseStock($adjustment_product->product_id, $adjustment_product->product_variant_id, $storedWarehouseId);
                } else {

                    $this->productStockUtil->adjustBranchStock($adjustment_product->product_id, $adjustment_product->product_variant_id, $storedBranchId);
                }
            }

            if ($storedStockAdjustmentAccountId) {

                $this->accountUtil->adjustAccountBalance(
                    balanceType: 'credit',
                    account_id: $storedStockAdjustmentAccountId
                );
            }

            if ($storedAccountId) {

                $this->accountUtil->adjustAccountBalance(
                    balanceType: 'debit',
                    account_id: $storedAccountId
                );
            }
        }

            return response()->json(__('Stock adjustment deleted successfully'));

    }

    // Search product
    public function searchProduct($keyword)
    {
        $__keyword = str_replace('~', '/', $keyword);

        $branch_id = auth()->user()->branch_id;

        $product = Product::with([
            'product_variants',
            'product_variants.updateVariantCost',
            'tax:id,tax_percent',
            'unit:id,name',
            'updateProductCost'
        ])->where('product_code', $__keyword)
            ->select([
                'id',
                'name',
                'type',
                'product_code',
                'product_price',
                'profit',
                'product_cost_with_tax',
                'thumbnail_photo',
                'unit_id',
                'tax_id',
                'tax_type',
                'is_show_emi_on_pos',
                'is_manage_stock',
            ])->first();

        return $this->nameSearchUtil->searchStockToBranch($product, $__keyword, $branch_id);
    }

    public function searchProductInWarehouse($keyword, $warehouse_id)
    {
        $__keyword = str_replace('~', '/', $keyword);

        $product = Product::with([
            'product_variants',
            'product_variants.updateVariantCost',
            'tax:id,tax_percent',
            'unit:id,name',
            'updateProductCost'
        ])->where('product_code', $__keyword)
            ->select([
                'id',
                'name',
                'type',
                'product_code',
                'product_price',
                'profit',
                'product_cost_with_tax',
                'thumbnail_photo',
                'unit_id',
                'tax_id',
                'tax_type',
                'is_show_emi_on_pos',
                'is_manage_stock',
            ])->first();

        return $this->nameSearchUtil->searchStockToWarehouse($product, $__keyword, $warehouse_id);
    }

    public function checkSingleProductStockInWarehouse($product_id, $warehouse_id)
    {
        return $this->nameSearchUtil->checkWarehouseSingleProduct($product_id, $warehouse_id);
    }

    public function checkVariantProductStockInWarehouse($product_id, $variant_id, $warehouse_id)
    {
        return $this->nameSearchUtil->checkWarehouseProductVariant($product_id, $variant_id, $warehouse_id);
    }

    public function checkSingleProductStock($product_id)
    {
        $branch_id = auth()->user()->branch_id;

        return $this->nameSearchUtil->checkBranchSingleProductStock($product_id, $branch_id);
    }

    public function checkVariantProductStock($product_id, $variant_id)
    {
        $branch_id = auth()->user()->branch_id;

        return $this->nameSearchUtil->checkBranchVariantProductStock($product_id, $variant_id, $branch_id);
    }
}
