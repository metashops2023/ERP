<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Utils\Converter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

define('TODAY_DATE', Carbon::today());

class DashboardController extends Controller
{
    protected $converter;

    public function __construct(Converter $converter)
    {
        // define('TODAY_DATE', date('Y-m-d'));
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    public function tester()
    {
        return TODAY_DATE;
    }
    // Admin dashboard
    public function index()
    {
        $thisWeek = Carbon::now()->startOfWeek()->format('Y-m-d') . '~' . Carbon::now()->endOfWeek()->format('Y-m-d');
        $thisYear = Carbon::now()->startOfYear()->format('Y-m-d') . '~' . Carbon::now()->endOfYear()->format('Y-m-d');
        $thisMonth = Carbon::now()->startOfMonth()->format('Y-m-d') . '~' . Carbon::now()->endOfMonth()->format('Y-m-d');
        $toDay = Carbon::now()->format('Y-m-d') . '~' . Carbon::now()->endOfDay()->format('Y-m-d');
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('dashboard.dashboard_1', compact('branches', 'thisWeek', 'thisYear', 'thisMonth', 'toDay'));
    }

    // Get dashboard card data
    public function cardData(Request $request)
    {
        $totalSales = 0;
        $totalSaleDue = 0;
        $totalSaleDiscount = 0;
        $totalPurchase = 0;
        $totalPurchaseDue = 0;
        $totalExpense = 0;

        $purchases = '';
        $sales = '';
        $expenses = '';
        $products = '';
        $users = '';
        $adjustments = '';

        $userQuery = DB::table('admin_and_users');
        $purchaseQuery = DB::table('purchases')->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            //DB::raw('sum(case when due > 0 then due end) as total_due'),
            DB::raw('sum(due) as total_due'),
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            //DB::raw('sum(case when due > 0 then due end) as total_due'),
            DB::raw('sum(due) as total_due'),
            DB::raw('sum(order_discount) as total_discount')
        );

        $expenseQuery = DB::table('expenses')->select(
            DB::raw('sum(net_total_amount) as total_expense'),
        );

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
        );

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $purchaseQuery->where('purchases.branch_id', NULL);
                $saleQuery->where('sales.branch_id', NULL)->where('sales.status', 1);
                $expenseQuery->where('expenses.branch_id', NULL);
                $userQuery->where('admin_and_users.branch_id', NULL);
                $adjustmentQuery->where('stock_adjustments.branch_id', NULL);
            } else {

                $purchaseQuery->where('purchases.branch_id', $request->branch_id);
                $saleQuery->where('sales.branch_id', $request->branch_id)->where('sales.status', 1);
                $expenseQuery->where('expenses.branch_id', $request->branch_id);
                $userQuery->where('admin_and_users.branch_id', $request->branch_id);
                $adjustmentQuery->where('stock_adjustments.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range != 'all_time') {
            if ($request->date_range) {

                $date_range = explode('~', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                $to_date = date('Y-m-d', strtotime($date_range[1]));

                $range = [Carbon::parse($form_date), Carbon::parse($to_date)->endOfDay()];

                $saleQuery->whereBetween('sales.report_date', $range); // Final
                $purchaseQuery->whereBetween('purchases.report_date', $range);
                $expenseQuery->whereBetween('expenses.report_date', $range);
                $adjustmentQuery->whereBetween('stock_adjustments.report_date_ts', $range);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $saleQuery->where('sales.status', 1)->get();
            $purchases = $purchaseQuery->get();
            $expenses = $expenseQuery->get();
            $users = $userQuery->count();
            $adjustments = $adjustmentQuery->get();
        } else {

            $sales = $saleQuery->where('sales.branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)->get();
            $purchases = $purchaseQuery->where('purchases.branch_id', auth()->user()->branch_id)->get();
            $expenses = $expenseQuery->where('expenses.branch_id', auth()->user()->branch_id)->get();
            $users = $userQuery->where('admin_and_users.branch_id', auth()->user()->branch_id)->count();
            $adjustments = $adjustmentQuery->where('stock_adjustments.branch_id', auth()->user()->branch_id)->get();
        }


        $totalSales = $sales->sum('total_sale');
        $totalSaleDue = $sales->sum('total_due');
        $totalSaleDiscount = $sales->sum('total_discount');

        $totalPurchase = $purchases->sum('total_purchase');
        $totalPurchaseDue = $purchases->sum('total_due');

        $totalExpense = $expenses->sum('total_expense');
        $products = DB::table('products')->count();
        $total_adjustment = $adjustments->sum('total_adjustment');

        return response()->json([
            'total_sale' =>  $this->converter->format_in_bdt($totalSales),
            'totalSaleDue' => $this->converter->format_in_bdt($totalSaleDue),
            'totalSaleDiscount' => $this->converter->format_in_bdt($totalSaleDiscount),
            'totalPurchase' => $this->converter->format_in_bdt($totalPurchase),
            'totalPurchaseDue' => $this->converter->format_in_bdt($totalPurchaseDue),
            'totalExpense' => $this->converter->format_in_bdt($totalExpense),
            'users' => $this->converter->format_in_bdt($users),
            'products' => $this->converter->format_in_bdt($products),
            'total_adjustment' => $this->converter->format_in_bdt($total_adjustment),
        ]);
    }

    public function stockAlert(Request $request)
    {
        if ($request->ajax()) {

            $alertQtyProducts = '';
            $alertQtyProducts = DB::table('product_branches')
                ->leftJoin('products', 'product_branches.product_id', 'products.id')
                ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
                ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
                ->join('units', 'products.unit_id', 'units.id')
                ->select(
                    [
                        'products.name',
                        'products.product_code',
                        'products.alert_quantity',
                        'product_branches.product_quantity',
                        'product_branch_variants.variant_quantity',
                        'product_variants.variant_name',
                        'units.name as unit_name',

                    ]
                )
                ->where('product_branches.branch_id', auth()->user()->branch_id)
                ->whereColumn('product_branch_variants.variant_quantity', '<=', 'products.alert_quantity')
                ->orWhereColumn('product_branches.product_quantity', '<=', 'products.alert_quantity')
                ->where('products.is_manage_stock', 1)
                ->orderBy('products.id', 'desc')
                ->get();


            // if ($request->branch_id) {

            //     if ($request->branch_id == 'NULL') {

            //         $query->where('product_branches.branch_id', NULL);
            //     } else {

            //         $query->where('product_branches.branch_id', $request->branch_id);
            //     }
            // }

            // if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            //     $alertQtyProducts = $query->get();
            // }

            return DataTables::of($alertQtyProducts)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {

                    return $row->name . ($row->variant_name != null ? '/' . $row->variant_name : '');
                })
                ->editColumn('stock', function ($row) {

                    return $quantity = '<span class="text-danger"><b>' . $row->product_quantity . '/' . $row->unit_name . '</b></span>';
                })
                ->rawColumns(['stock'])->make(true);
        }
    }

    public function saleOrder(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $sales = '';
            $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id')
                ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('sales.branch_id', NULL);
                } else {

                    $query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->date_range != 'all_time') {

                if ($request->date_range) {

                    $date_range = explode('~', $request->date_range);
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));

                    $range = [Carbon::parse($form_date), Carbon::parse($to_date)->endOfDay()];

                    $query->whereBetween('sales.report_date', [$range]); // Final
                }
            }

            $query->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
                'admin_and_users.prefix as c_prefix',
                'admin_and_users.name as c_name',
                'admin_and_users.last_name as c_last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $sales = $query->orderBy('id', 'desc')->where('sales.shipment_status', 1)->get();
            } else {

                $sales = $query->where('sales.branch_id', auth()->user()->branch_id)
                    ->where('sales.shipment_status', 1)->get();
            }

            return DataTables::of($sales)
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name']  . '(<b>HO</b>)';
                    }
                })
                ->editColumn('shipment_status',  function ($row) {

                    if ($row->shipment_status == 1) {

                        return '<span class="badge bg-warning">Ordered</span>';
                    }
                })
                ->editColumn('customer',  function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('created_by',  function ($row) {

                    return $row->c_prefix . ' ' . $row->c_name . ' ' . $row->c_last_name;
                })
                ->rawColumns(['date', 'from', 'customer', 'created_by', 'shipment_status'])
                ->make(true);
        }
    }

    public function saleDue(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $sales = '';
            $query = DB::table('sales')
                ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('customers', 'sales.customer_id', 'customers.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('sales.branch_id', NULL);
                } else {

                    $query->where('sales.branch_id', $request->branch_id);
                }
            }

            if ($request->date_range != 'all_time') {

                if ($request->date_range) {

                    $date_range = explode('~', $request->date_range);
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));

                    $range = [Carbon::parse($form_date), Carbon::parse($to_date)->endOfDay()];
                    $query->whereBetween('sales.report_date', $range); // Final
                }
            }

            $query->select(
                'sales.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'customers.name as customer_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $sales = $query->where('sales.due', '>', 0)->where('sales.status', 1)->orderBy('id', 'desc')->get();
            } else {

                $sales = $query->where('sales.branch_id', auth()->user()->branch_id)
                    ->where('sales.due', '>', 0)->where('sales.status', 1)->get();
            }

            return DataTables::of($sales)
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name']  . '(<b>HO</b>)';
                    }
                })
                ->editColumn('customer',  function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })
                ->editColumn('due',  function ($row) use ($generalSettings) {

                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->due;
                })
                ->rawColumns(['date', 'from', 'customer', 'due'])
                ->make(true);
        }
    }

    public function purchaseDue(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $purchases = '';
            $query = DB::table('purchases')
                ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
                ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('purchases.branch_id', NULL);
                } else {
                    $query->where('purchases.branch_id', $request->branch_id);
                }
            }

            if ($request->date_range != 'all_time') {

                if ($request->date_range) {

                    $date_range = explode('~', $request->date_range);
                    $form_date = date('Y-m-d', strtotime($date_range[0]));
                    $to_date = date('Y-m-d', strtotime($date_range[1]));
                    $range = [Carbon::parse($form_date), Carbon::parse($to_date)->endOfDay()];
                    $query->whereBetween('purchases.report_date', $range); // Final
                }
            }

            $query->select(
                'purchases.*',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'branches.branch_code',
                'suppliers.name as sup_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $purchases = $query->where('purchases.due', '!=', 0)->orderBy('id', 'desc')->get();
            } else {

                $purchases = $query->where('purchases.branch_id', auth()->user()->branch_id)
                    ->where('purchases.due', '!=', 0)->get();
            }

            return DataTables::of($purchases)
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) use ($generalSettings) {

                    if ($row->branch_name) {

                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {

                        return json_decode($generalSettings->business, true)['shop_name']  . '(<b>HO</b>)';
                    }
                })
                ->editColumn('due',  function ($row) use ($generalSettings) {

                    return json_decode($generalSettings->business, true)['currency'] . ' ' . $row->due;
                })
                ->rawColumns(['date', 'from', 'due'])
                ->make(true);
        }
    }

    public function todaySummery(Request $request)
    {
        $branch = '';
        $totalSales = 0;
        $totalSaleDue = 0;
        $totalReceive = 0;
        $totalSaleDiscount = 0;
        $totalSalesReturn = 0;
        $totalSalesShipmentCost = 0;
        $totalPurchase = 0;
        $totalPurchaseDue = 0;
        $totalPayment = 0;
        $totalPurchaseReturn = 0;
        $totalExpense = 0;
        $total_recovered = 0;
        $totalTransferShippingCost = 0;
        $purchaseTotalShipmentCost = 0;
        $totalPayroll = 0;

        $purchases = '';
        $purchasePayment = '';
        $supplierPayment = '';
        $purchaseReturn = '';
        $purchaseTotalShipmentCost = '';
        $sales = '';
        $customerPayment = '';
        $salePayment = '';
        $branchTransfer = '';
        $warehouseTransfer = '';
        $saleReturn = '';
        $expenses = '';
        $adjustments = '';
        $payrolls = '';

        $purchaseQuery = DB::table('purchases')->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            DB::raw('sum(shipment_charge) as total_shipment_charge'),
            DB::raw('sum(due) as total_due')
        );

        $supplierPaymentQ = DB::table('supplier_payments')
            ->where('supplier_payments.type', 1)
            ->select(
                DB::raw('sum(supplier_payments.paid_amount) as t_paid'),
            );

        $purchasePaymentQ = DB::table('purchase_payments')
            ->where('purchase_payments.supplier_payment_id', NULL)
            ->where('purchase_payments.payment_type', 1)
            ->select(
                DB::raw('sum(paid_amount) as total_paid'),
            );

        $purchaseReturnQuery = DB::table('purchase_returns')->select(
            DB::raw('sum(total_return_amount) as total_return')
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(order_discount) as total_discount'),
            DB::raw('sum(shipment_charge) as total_shipment_charge'),
            DB::raw('sum(order_tax_amount) as total_order_tax'),
            DB::raw('sum(due) as total_due'),
        );

        $customerPaymentQ = DB::table('customer_payments')
            ->where('customer_payments.type', 1)
            ->select(
                DB::raw('sum(customer_payments.paid_amount) as t_paid'),
            );

        $salePaymentQ = DB::table('sale_payments')
            ->where('sale_payments.customer_payment_id', NULL)
            ->where('sale_payments.payment_type', 1)
            ->select(
                DB::raw('sum(paid_amount) as total_paid'),
            );

        $saleReturnQuery = DB::table('sale_returns')
            ->select(DB::raw('sum(total_return_amount) as total_return'));

        $expenseQuery = DB::table('expenses')->select(DB::raw('sum(net_total_amount) as total_expense'));

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );

        $branchTransferQuery = DB::table('transfer_stock_to_branches')->select(
            DB::raw('sum(shipping_charge) as total_shipping_cost_br')
        );

        $warehouseTransferQuery = DB::table('transfer_stock_to_warehouses')->select(
            DB::raw('sum(shipping_charge) as total_shipping_cost_wh')
        );

        $payrollQuery = DB::table('hrm_payroll_payments')
            ->leftJoin('hrm_payrolls', 'hrm_payroll_payments.payroll_id', 'hrm_payrolls.id')
            ->leftJoin('admin_and_users', 'hrm_payrolls.user_id', 'admin_and_users.id')
            ->select(DB::raw('sum(hrm_payroll_payments.paid) as total_payroll'));

        if ($request->branch_id) {

            if ($request->branch_id == 'HF') {

                $purchaseQuery->where('purchases.branch_id', NULL);
                $supplierPaymentQ->where('supplier_payments.branch_id', NULL);
                $purchasePaymentQ->where('purchase_payments.branch_id', NULL);
                $customerPaymentQ->where('customer_payments.branch_id', NULL);
                $salePaymentQ->where('sale_payments.branch_id', NULL);
                $saleQuery->where('sales.branch_id', NULL);
                $expenseQuery->where('expenses.branch_id', NULL);
                $adjustmentQuery->where('stock_adjustments.branch_id', NULL);
                $purchaseReturnQuery->where('purchase_returns.branch_id', NULL);
                $saleReturnQuery->where('sale_returns.branch_id', NULL);
                $branchTransferQuery->where('transfer_stock_to_branches.branch_id', NULL);
                $warehouseTransferQuery->where('transfer_stock_to_warehouses.branch_id', NULL);
                $payrollQuery->where('admin_and_users.branch_id', NULL);
            } else {

                $purchaseQuery->where('purchases.branch_id', $request->branch_id);
                $supplierPaymentQ->where('supplier_payments.branch_id', $request->branch_id);
                $purchasePaymentQ->where('purchase_payments.branch_id', $request->branch_id);
                $customerPaymentQ->where('customer_payments.branch_id', $request->branch_id);
                $salePaymentQ->where('sale_payments.branch_id', $request->branch_id);
                $saleQuery->where('sales.branch_id', $request->branch_id);
                $expenseQuery->where('expenses.branch_id', $request->branch_id);
                $adjustmentQuery->where('stock_adjustments.branch_id', $request->branch_id);
                $purchaseReturnQuery->where('purchase_returns.branch_id', $request->branch_id);
                $saleReturnQuery->where('sale_returns.branch_id', $request->branch_id);
                $branchTransferQuery->where('transfer_stock_to_branches.branch_id', $request->branch_id);
                $warehouseTransferQuery->where('transfer_stock_to_warehouses.branch_id', $request->branch_id);
                $payrollQuery->where('admin_and_users.branch_id', $request->branch_id);
                $branch = DB::table('branches')->where('id', $request->branch_id)
                    ->select('name', 'branch_code')
                    ->first();
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $saleQuery->where('sales.status', 1)->whereDate('report_date', TODAY_DATE)->get();
            $purchases = $purchaseQuery->whereDate('report_date', TODAY_DATE)->get();
            $supplierPayment = $supplierPaymentQ->whereDate('supplier_payments.report_date', TODAY_DATE)->get();
            $purchasePayment = $purchasePaymentQ->whereDate('purchase_payments.report_date', TODAY_DATE)->get();
            $customerPayment = $customerPaymentQ->whereDate('customer_payments.report_date', TODAY_DATE)->get();
            $salePayment = $salePaymentQ->whereDate('sale_payments.report_date', TODAY_DATE)->get();
            $expenses = $expenseQuery->whereDate('report_date', TODAY_DATE)->get();
            $adjustments = $adjustmentQuery->whereDate('report_date_ts', TODAY_DATE)->get();
            $purchaseReturn = $purchaseReturnQuery->whereDate('report_date', TODAY_DATE)->get();
            $saleReturn = $saleReturnQuery->whereDate('report_date', TODAY_DATE)->get();
            $branchTransfer = $branchTransferQuery->whereDate('report_date', TODAY_DATE)->get();
            $warehouseTransfer = $warehouseTransferQuery->whereDate('report_date', TODAY_DATE)->get();
            $payrolls = $payrollQuery->whereDate('hrm_payroll_payments.report_date', TODAY_DATE)->get();
        } else {

            $sales = $saleQuery->where('sales.branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)->whereDate('report_date', TODAY_DATE)->get();

            $purchases = $purchaseQuery->where('purchases.branch_id', auth()->user()->branch_id)->whereDate('report_date', TODAY_DATE)->get();

            $supplierPayment = $supplierPaymentQ->where('supplier_payments.branch_id', auth()->user()->branch_id)->whereDate('supplier_payments.report_date', TODAY_DATE)->get();

            $purchasePayment = $purchasePaymentQ->where('purchase_payments.branch_id', auth()->user()->branch_id)->whereDate('purchase_payments.report_date', TODAY_DATE)->get();

            $customerPayment = $customerPaymentQ->where('customer_payments.branch_id', auth()->user()->branch_id)->whereDate('customer_payments.report_date', TODAY_DATE)->get();

            $salePayment = $salePaymentQ->where('sale_payments.branch_id', auth()->user()->branch_id)->whereDate('sale_payments.report_date', TODAY_DATE)->get();

            $expenses = $expenseQuery->where('expenses.branch_id', auth()->user()->branch_id)->whereDate('report_date', TODAY_DATE)->get();

            $adjustments = $adjustmentQuery->where('stock_adjustments.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date_ts', TODAY_DATE)->get();

            $purchaseReturn = $purchaseReturnQuery->where('purchase_returns.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date', TODAY_DATE)->get();

            $saleReturn = $saleReturnQuery->where('sale_returns.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date', TODAY_DATE)->get();

            $branchTransfer = $branchTransferQuery->where('transfer_stock_to_branches.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date', TODAY_DATE)->get();

            $warehouseTransfer = $warehouseTransferQuery->where('transfer_stock_to_warehouses.branch_id', auth()->user()->branch_id)
                ->whereDate('report_date', TODAY_DATE)->get();

            $payrolls = $payrollQuery->whereDate('hrm_payroll_payments.report_date', TODAY_DATE)
                ->where('admin_and_users.branch_id', auth()->user()->branch_id)->get();
        }

        $totalSales = $sales->sum('total_sale');
        $totalSaleDue = $sales->sum('total_due');
        $totalReceive = $customerPayment->sum('t_paid') + $salePayment->sum('total_paid');
        $totalSaleDiscount = $sales->sum('total_discount');
        $totalSaleTax = $sales->sum('total_order_tax');
        $totalSalesReturn = $saleReturn->sum('total_return');
        $totalSalesShipmentCost = $sales->sum('total_shipment_charge');
        $totalPurchase = $purchases->sum('total_purchase');
        $totalPayment = $supplierPayment->sum('t_paid') + $purchasePayment->sum('total_paid');
        $totalPurchaseDue = $purchases->sum('total_due');
        $totalPurchaseReturn = $purchaseReturn->sum('total_return');
        $totalExpense = $expenses->sum('total_expense');
        $total_adjustment = $adjustments->sum('total_adjustment');
        $total_recovered = $adjustments->sum('total_recovered');
        $totalTransferShippingCost = $branchTransfer->sum('total_shipping_cost_br') + $warehouseTransfer->sum('total_shipping_cost_wh');
        $purchaseTotalShipmentCost = $purchases->sum('total_shipment_charge');

        $totalPayroll = $payrolls->sum('total_payroll');
        $branch_id = $request->branch_id;

        $todayProfitParameters = [
            $total_adjustment,
            $total_recovered,
            $totalSales,
            $totalSalesReturn,
            $totalSaleTax,
            $totalExpense,
            $totalPayroll,
            $totalTransferShippingCost,
            $request->branch_id
        ];

        $todayProfit = $this->todayProfit(...$todayProfitParameters);

        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('dashboard.ajax_view.today_summery', compact(
            'totalSales',
            'totalSaleDue',
            'totalReceive',
            'totalSaleDiscount',
            'totalSalesReturn',
            'totalSalesShipmentCost',
            'totalPurchase',
            'totalPurchaseDue',
            'totalPayment',
            'totalPurchaseReturn',
            'totalExpense',
            'total_adjustment',
            'total_recovered',
            'totalTransferShippingCost',
            'purchaseTotalShipmentCost',
            'totalPayroll',
            'branches',
            'branch',
            'branch_id',
            'todayProfit'
        ));
    }

    public function todayProfit($totalAdjust, $totalRecovered, $totalSale, $totalSalesReturn, $totalOrderTax, $totalExpense, $totalPayroll, $totalTransferCost, $branch_id)
    {
        // $saleProductQuery = DB::table('sale_products')->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
        //     ->select(DB::raw('sum(quantity * unit_cost_inc_tax) as total_unit_cost'));

        $saleProductQuery = DB::table('purchase_sale_product_chains')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->select(
                DB::raw('SUM(net_unit_cost * sold_qty) as total_unit_cost')
            );

        if ($branch_id) {
            if ($branch_id == 'HF') {
                $saleProductQuery->where('sales.branch_id', NULL);
            } else {
                $saleProductQuery->where('sales.branch_id', $branch_id);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $saleProducts = $saleProductQuery->where('sales.status', 1)
                ->whereDate('sales.report_date', TODAY_DATE)->get();
        } else {
            $saleProducts = $saleProductQuery->where('sales.status', 1)
                ->whereDate('sales.report_date', TODAY_DATE)->where('sales.branch_id', auth()->user()->branch_id)->get();
        }

        $totalTotalUnitCost = $saleProducts->sum('total_unit_cost');

        return $netProfit = ($totalSale + $totalRecovered)
            - $totalAdjust
            - $totalExpense
            - $totalSalesReturn
            - $totalOrderTax
            - $totalPayroll
            - $totalTotalUnitCost
            - $totalTransferCost;
    }

    public function changeLang($lang)
    {
        session(['lang' => $lang]);
        return redirect()->back();
    }
}
