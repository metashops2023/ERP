<?php

namespace App\Http\Controllers;

use Exception;
use App\Utils\Util;
use App\Models\Sale;
use App\Utils\SmsUtil;
use App\Models\Product;
use App\Utils\SaleUtil;
use App\Models\Customer;
use App\Jobs\SaleMailJob;
use App\Utils\AccountUtil;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Utils\CustomerUtil;
use App\Utils\PurchaseUtil;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ProductBranch;
use App\Utils\NameSearchUtil;
use App\Models\General_setting;
use App\Utils\ProductStockUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Utils\InvoiceVoucherRefIdUtil;

class SaleController extends Controller
{
    protected $nameSearchUtil;
    protected $saleUtil;
    protected $smsUtil;
    protected $util;
    protected $customerUtil;
    protected $productStockUtil;
    protected $accountUtil;
    protected $invoiceVoucherRefIdUtil;
    protected $purchaseUtil;
    protected $userActivityLogUtil;
    public function __construct(
        NameSearchUtil $nameSearchUtil,
        SaleUtil $saleUtil,
        SmsUtil $smsUtil,
        Util $util,
        CustomerUtil $customerUtil,
        ProductStockUtil $productStockUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        PurchaseUtil $purchaseUtil,
        UserActivityLogUtil $userActivityLogUtil
    ) {
        $this->nameSearchUtil = $nameSearchUtil;
        $this->saleUtil = $saleUtil;
        $this->smsUtil = $smsUtil;
        $this->util = $util;
        $this->customerUtil = $customerUtil;
        $this->productStockUtil = $productStockUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index2(Request $request)
    {
        if (auth()->user()->permission->sale['view_add_sale'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->saleUtil->addSaleTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);
        return view('sales.index2', compact('branches', 'customers'));
    }

    public function posList(Request $request)
    {
        if (auth()->user()->permission->sale['pos_all'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->saleUtil->posSaleTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);
        return view('sales.pos.index', compact('branches', 'customers'));
    }

    public function salesOrderList(Request $request)
    {
        if ($request->ajax()) {

            return $this->saleUtil->SaleOrderTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);

        return view('sales.orders', compact('branches', 'customers'));
    }

    public function soldProductList(Request $request)
    {
        if ($request->ajax()) {

            return $this->saleUtil->soldProductListTable($request);
        }

        $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);
        return view('sales.sold_product_list', compact('branches', 'categories', 'customers'));
    }

    public function show($saleId)
    {
        $sale = Sale::with([
            'branch',
            'branch.add_sale_invoice_layout',
            'customer:id,name,phone,alternative_phone,city,state,country,landline,email,address,tax_number,point',
            'admin:id,prefix,name,last_name,role_id',
            'admin.role',
            'sale_products',
            'sale_products.product:id,name,product_code,warranty_id,unit_id,tax_id',
            'sale_products.product.warranty',
            'sale_products.variant:id,variant_name,variant_code',
            'sale_payments',
            'sale_payments.paymentMethod:id,name',
        ])->where('id', $saleId)->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($saleId);

        return view('sales.ajax_view.product_details_modal', compact('sale', 'customerCopySaleProducts'));
    }

    public function posShow($saleId)
    {
        $sale = Sale::with([
            'branch',
            'branch.pos_sale_invoice_layout',
            'customer',
            'admin',
            'admin.role',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'sale_payments',
            'sale_payments.paymentMethod:id,name',
        ])->where('id', $saleId)->first();
        return view('sales.pos.ajax_view.show', compact('sale'));
    }

    // Draft list view
    public function drafts(Request $request)
    {
        if ($request->ajax()) {

            return $this->saleUtil->saleDraftTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('sales.drafts', compact('branches'));
    }

    public function draftDetails($draftId)
    {
        $draft = Sale::with([
            'branch', 'branch.add_sale_invoice_layout', 'customer', 'admin:id,prefix,name,last_name,role_id', 'admin.role', 'sale_products', 'sale_products.branch', 'sale_products.warehouse', 'sale_products.product:id,name,product_code', 'sale_products.variant:id,variant_name,variant_code', 'sale_payments',
        ])->where('id', $draftId)->first();

        // $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($quotation->id);

        return view('sales.ajax_view.draft_details', compact('draft'));
    }

    // Quotations list view
    public function quotations(Request $request)
    {
        if ($request->ajax()) {

            return $this->saleUtil->saleQuotationTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('sales.quotations', compact('branches'));
    }

    // Quotation Details
    public function quotationDetails($quotationId)
    {
        $quotation = Sale::with([
            'branch', 'branch.add_sale_invoice_layout', 'customer', 'admin:id,prefix,name,last_name,role_id', 'admin.role', 'sale_products', 'sale_products.branch', 'sale_products.warehouse', 'sale_products.product:id,name,product_code', 'sale_products.variant:id,variant_name,variant_code', 'sale_payments',
        ])->where('id', $quotationId)->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($quotation->id);

        return view('sales.ajax_view.quotation_details', compact('quotation', 'customerCopySaleProducts'));
    }

    // Create sale view
    public function create()
    {
        if (auth()->user()->permission->sale['create_add_sale'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        $branch_id = auth()->user()->branch_id;

        $customers = DB::table('customers')
            ->where('status', 1)->select('id', 'name', 'phone')
            ->orderBy('id', 'desc')->get();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $invoice_schemas = DB::table('invoice_schemas')->get(['format', 'prefix', 'start_from']);

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $saleAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', $branch_id)
            ->where('accounts.account_type', 5)
            ->get(['accounts.id', 'accounts.name']);

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', $branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->get();

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        return view('sales.create', compact(
            'customers',
            'methods',
            'accounts',
            'saleAccounts',
            'price_groups',
            'invoice_schemas',
            'warehouses'
        ));
    }

    // Add Sale method
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sale A/C is required',
            'account_id.required' => 'Debit A/C is required',
        ]);

        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly

            $settings = DB::table('general_settings')
                ->select(['id', 'business', 'prefix', 'send_es_settings'])
                ->first();

            if ($request->status == 3 && !$request->customer_id) {

                return response()->json(['errorMsg' => 'Listed customer is required for sales order.']);
            }

            if ($request->customer_id && ($request->status == 1 || $request->status == 3)) {

                if ($request->total_due > 0) {

                    $customerCreditLimit = DB::table('customer_credit_limits')->where('customer_id', $request->customer_id)->where('branch_id', auth()->user()->branch_id)
                        ->select('credit_limit')
                        ->first();

                    $creditLimit = $customerCreditLimit ? $customerCreditLimit->credit_limit : 0;
                    $__credit_limit = $creditLimit ? $creditLimit : 0;
                    $msg_1 = 'Customer does not have any credit limit.';
                    $msg_2 = "Customer Credit Limit is ${__credit_limit}.";
                    $__show_msg = $__credit_limit ? $msg_2 : $msg_1;

                    if ($request->total_due > $__credit_limit) {

                        return response()->json(['errorMsg' => $__show_msg]);
                    }
                }
            }

            if (
                $request->paying_amount < $request->total_payable_amount &&
                !$request->customer_id &&
                ($request->status == 1 || $request->status == 3)
            ) {

                return response()->json(['errorMsg' => 'Listed customer is required when sale is due or partial.']);
            }

            $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];

            $paymentInvoicePrefix = json_decode($settings->prefix, true)['sale_payment'];

            $branchInvoiceSchema = DB::table('branches')
                ->leftJoin('invoice_schemas', 'branches.invoice_schema_id', 'invoice_schemas.id')
                ->where('branches.id', auth()->user()->branch_id)
                ->select(
                    'branches.*',
                    'invoice_schemas.id as schema_id',
                    'invoice_schemas.prefix',
                    'invoice_schemas.format',
                    'invoice_schemas.start_from',
                )->first();

            $invoicePrefix = '';

            if ($request->invoice_schema) {

                $invoicePrefix = $request->invoice_schema;
            } else {

                if ($branchInvoiceSchema && $branchInvoiceSchema->prefix !== null) {

                    $invoicePrefix = $branchInvoiceSchema->format == 2 ? date('Y') . $branchInvoiceSchema->start_from : $branchInvoiceSchema->prefix . $branchInvoiceSchema->start_from;
                } else {

                    $defaultSchemas = DB::table('invoice_schemas')->where('is_default', 1)->first();
                    $invoicePrefix = $defaultSchemas->format == 2 ? date('Y') . $defaultSchemas->start_from : $defaultSchemas->prefix . $defaultSchemas->start_from;
                }
            }

            if ($request->product_ids == null) {

                return response()->json(['errorMsg' => 'product table is empty']);
            }

            $invoiceId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('sales'), 5, "0", STR_PAD_LEFT);

            $addSale = new Sale();
            $addSale->invoice_id = $request->invoice_id ? $request->invoice_id : $invoicePrefix . $invoiceId;
            $addSale->admin_id = auth()->user()->id;
            $addSale->sale_account_id = $request->sale_account_id;
            $addSale->branch_id = auth()->user()->branch_id;
            $addSale->customer_id = $request->customer_id;
            $addSale->status = $request->status;

            if ($request->status == 1) {

                $addSale->is_fixed_challen = 1;
            }

            $addSale->pay_term = $request->pay_term;
            $addSale->date = $request->date;
            $addSale->time = date('h:i:s a');
            $addSale->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
            $addSale->pay_term_number = $request->pay_term_number;
            $addSale->total_item = $request->total_item;
            $addSale->net_total_amount = $request->net_total_amount;
            $addSale->order_discount_type = $request->order_discount_type;
            $addSale->order_discount = $request->order_discount;
            $addSale->order_discount_amount = $request->order_discount_amount;
            $addSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
            $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
            $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
            $addSale->shipment_details = $request->shipment_details;
            $addSale->shipment_address = $request->shipment_address;
            $addSale->shipment_status = $request->shipment_status;
            $addSale->delivered_to = $request->delivered_to;
            $addSale->sale_note = $request->sale_note;
            $addSale->payment_note = $request->payment_note;
            $addSale->month = date('F');
            $addSale->year = date('Y');

            $addSale->previous_due = $request->previous_due;
            $addSale->gross_pay = $request->paying_amount;
            $addSale->all_total_payable = $request->total_payable_amount;
            $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0.00;

            // Update customer due
            $invoicePayable = 0;

            if ($request->status == 1 || $request->status == 3) {

                $changedAmount = $request->change_amount > 0 ? $request->change_amount : 0;
                $paidAmount = $request->paying_amount - $changedAmount;

                if ($request->previous_due != 0) {

                    $invoicePayable = $request->total_invoice_payable;

                    $addSale->total_payable_amount = $request->total_invoice_payable;

                    if ($paidAmount >= $request->total_invoice_payable) {

                        $addSale->paid = $request->total_invoice_payable;
                        $addSale->due = 0.00;
                    } elseif ($request->paying_amount == 0 && $request->total_payable_amount < 0) {

                        $addSale->paid = $request->paying_amount;
                        $calcDue = $request->total_payable_amount;
                        $addSale->due = $request->total_payable_amount;
                    } elseif ($paidAmount < $request->total_invoice_payable) {

                        $addSale->paid = $request->paying_amount;
                        $calcDue = $request->total_invoice_payable - $request->paying_amount;
                        $addSale->due = $calcDue;
                    }
                } else {

                    $invoicePayable = $request->total_payable_amount;

                    $addSale->total_payable_amount = $request->total_payable_amount;
                    // $addSale->paid = $request->change_amount > 0 ? $request->total_invoice_payable : $request->paying_amount;
                    $addSale->paid = $paidAmount;
                    $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0.00;
                    $addSale->due = $request->total_due > 0 ? $request->total_due : 0.00;
                }

                $addSale->save();

                // Add sales A/C ledger
                $this->accountUtil->addAccountLedger(
                    voucher_type_id: 1,
                    date: $request->date,
                    account_id: $request->sale_account_id,
                    trans_id: $addSale->id,
                    amount: $invoicePayable,
                    balance_type: 'credit'
                );

                if ($request->customer_id) {

                    // Add customer ledger
                    $this->customerUtil->addCustomerLedger(
                        voucher_type_id: 1,
                        customer_id: $request->customer_id,
                        branch_id: auth()->user()->branch_id,
                        date: $request->date,
                        trans_id: $addSale->id,
                        amount: $invoicePayable
                    );
                }
            } else {

                $addSale->total_payable_amount = $request->total_invoice_payable;
                $addSale->save();
            }

            // update product quantity and add sale product
            $branch_id = auth()->user()->branch_id;

            $__index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : NULL;
                $warehouse_id = $request->warehouse_ids[$__index] == 'NULL' ? NULL : $request->warehouse_ids[$__index];
                $addSaleProduct = new SaleProduct();
                $addSaleProduct->sale_id = $addSale->id;
                $addSaleProduct->stock_warehouse_id = $warehouse_id;
                $addSaleProduct->stock_branch_id = $request->branch_ids[$__index];
                $addSaleProduct->product_id = $product_id;
                $addSaleProduct->product_variant_id = $variant_id;
                $addSaleProduct->quantity = $request->quantities[$__index];
                $addSaleProduct->unit_discount_type = $request->unit_discount_types[$__index];
                $addSaleProduct->unit_discount = $request->unit_discounts[$__index];
                $addSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$__index];
                $addSaleProduct->unit_tax_percent = $request->unit_tax_percents[$__index];
                $addSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$__index];
                $addSaleProduct->unit = $request->units[$__index];
                $addSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$__index];
                $addSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$__index];
                $addSaleProduct->unit_price_inc_tax = $request->unit_prices[$__index];
                $addSaleProduct->subtotal = $request->subtotals[$__index];
                $addSaleProduct->description = $request->descriptions[$__index] ? $request->descriptions[$__index] : NULL;
                $addSaleProduct->save();

                $__index++;
            }

            // Add sale payment
            $sale = Sale::with([
                'customer',
                'branch',
                'branch.add_sale_invoice_layout',
                'sale_products',
                'sale_products.product:id,name,product_code,is_manage_stock',
                'sale_products.variant:id,variant_name,variant_code',
                'sale_products.branch',
                'sale_products.warehouse',
                'admin:id,prefix,name,last_name'
            ])->where('id', $addSale->id)->first();

            if ($request->status == 1 || $request->status == 3) {

                $this->saleUtil->__getSalePaymentForAddSaleStore(
                    $request,
                    $sale,
                    $paymentInvoicePrefix
                );

                $this->userActivityLogUtil->addLog(action: 1, subject_type: $request->status == 1 ? 7 : 8, data_obj: $sale);
            }

            if ($request->status == 1) {

                $__index = 0;
                foreach ($request->product_ids as $product_id) {

                    $warehouse_id = $request->warehouse_ids[$__index] == 'NULL' ? NULL : $request->warehouse_ids[$__index];
                    $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : NULL;

                    $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);

                    if ($warehouse_id) {

                        $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $warehouse_id);
                    } else {

                        $this->productStockUtil->adjustBranchStock($product_id, $variant_id, $branch_id);
                    }

                    $__index++;
                }

                $this->saleUtil->addPurchaseSaleProductChain($sale, $stockAccountingMethod);
            }

            $previous_due = $request->previous_due;
            $total_payable_amount = $request->total_payable_amount;
            $paying_amount = $request->paying_amount;
            $total_due = $request->total_due;
            $change_amount = $request->change_amount;

            if (
                env('MAIL_ACTIVE') == 'true' &&
                json_decode($settings->send_es_settings, true)['send_inv_via_email'] == '1'
            ) {

                if ($sale->customer && $sale->customer->email) {

                    SaleMailJob::dispatch($sale->customer->email, $sale)
                        ->delay(now()->addSeconds(5));
                }
            }

            if (
                env('SMS_ACTIVE') == 'true' &&
                json_decode($settings->send_es_settings, true)['send_notice_via_sms'] == '1'
            ) {

                if ($sale->customer && $sale->customer->phone) {

                    $this->smsUtil->singleSms($sale);
                }
            }

            $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($sale->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            if ($request->status == 1 || $request->status == 3) {

                return view('sales.save_and_print_template.sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount',
                    'customerCopySaleProducts'
                ));
            } elseif ($request->status == 2) {

                return view('sales.save_and_print_template.draft_print', compact('sale', 'customerCopySaleProducts'));
            } elseif ($request->status == 4) {

                return view('sales.save_and_print_template.quotation_print', compact('sale', 'customerCopySaleProducts'));
            }
        } else {

            if ($request->status == 1) {

                session()->flash('successMsg', 'Sale created successfully');

                    return response()->json(['finalMsg' => __('Sale created successfully')]);


            } elseif ($request->status == 2) {

                session()->flash('successMsg', 'Sale draft created successfully');

                    return response()->json(['draftMsg' => __('Sale draft created successfully')]);

            } elseif ($request->status == 4) {

                session()->flash('successMsg', 'Sales quotation created successfully');

                    return response()->json(['quotationMsg' => __('Sales quotation created successfully')]);

            }
        }
    }

    // Sale edit view
    public function edit($saleId)
    {
        if (auth()->user()->permission->sale['edit_add_sale'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get();

        $sale = Sale::with([
            'sale_products',
            'customer',
            'sale_products.branch',
            'sale_products.warehouse',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts',
            'sale_products.product.comboProducts.parentProduct',
            'sale_products.product.comboProducts.product_variant',
        ])->where('id', $saleId)->first();

        $qty_limits = $this->saleUtil->getStockLimitProducts($sale);

        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();

        $saleAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 5)
            ->get(['accounts.id', 'accounts.name']);

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->get();

        return view('sales.edit', compact('sale', 'price_groups', 'saleAccounts', 'taxes', 'qty_limits', 'methods', 'accounts', 'warehouses'));
    }

    // Update Sale
    public function update(Request $request, $saleId)
    {
        if (auth()->user()->permission->sale['edit_add_sale'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sale A/C is required',
        ]);

        $settings = DB::table('general_settings')->select(['id', 'business', 'prefix'])->first();

        $invoicePrefix = json_decode($settings->prefix, true)['sale_invoice'];

        $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'product table is empty']);
        }

        $updateSale = Sale::with([
            'sale_products',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts'
        ])->where('id', $saleId)->first();

        if ($updateSale->customer_id && ($request->status == 1 || $request->status == 3)) {

            if ($request->total_due > 0) {

                $customer = DB::table('customers')->where('id', $updateSale->customer_id)
                    ->select('credit_limit', 'total_sale_due')
                    ->first();

                $presentDue = $customer->total_sale_due + $request->total_due;
                $__credit_limit = $customer->credit_limit ? $customer->credit_limit : 0;

                $msg_1 = 'Customer does not have any credit limit.';
                $msg_2 = "Customer Credit Limit is ${__credit_limit}.";

                $__show_msg = $customer->credit_limit ? $msg_2 : $msg_1;

                if ($presentDue > $__credit_limit) {

                    return response()->json(['errorMsg' => $__show_msg]);
                }
            }
        }

        if (
            $request->paying_amount < $request->current_receivable &&
            !$updateSale->customer_id &&
            ($request->status == 1 || $request->status == 3)
        ) {

            return response()->json(['errorMsg' => 'Listed customer is required when sale is due or partial.']);
        }

        if ($updateSale->status == 1 && $request->status != 1) {

            return response()->json(['errorMsg' => 'Final sale you can not update to quotation, draft, order.']);
        }

        foreach ($updateSale->sale_products as $sale_product) {

            $sale_product->delete_in_update = 1;
            $sale_product->save();
        }

        $invoiceId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('sales'), 5, "0", STR_PAD_LEFT);

        $updateSale->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . $invoiceId;
        $updateSale->status = $request->status;
        $updateSale->sale_account_id = $request->sale_account_id;
        $updateSale->pay_term = $request->pay_term;
        $updateSale->date = $request->date;
        $updateSale->pay_term_number = $request->pay_term_number;
        $updateSale->total_item = $request->total_item;
        $updateSale->net_total_amount = $request->net_total_amount;
        $updateSale->order_discount_type = $request->order_discount_type;
        $updateSale->order_discount = $request->order_discount;
        $updateSale->order_discount_amount = $request->order_discount_amount;
        $updateSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $updateSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $updateSale->total_payable_amount = $request->total_payable_amount;
        $updateSale->shipment_details = $request->shipment_details;
        $updateSale->shipment_address = $request->shipment_address;
        $updateSale->shipment_status = $request->shipment_status;
        $updateSale->delivered_to = $request->delivered_to;
        $updateSale->sale_note = $request->sale_note;
        $updateSale->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $updateSale->gross_pay = $request->previous_paid + $request->paying_amount;
        $updateSale->save();

        if ($updateSale->status == 1 || $request->status == 3) {

            // Update Sales A/C Ledger
            $this->accountUtil->updateAccountLedger(
                voucher_type_id: 1,
                date: $request->date,
                account_id: $request->sale_account_id,
                trans_id: $updateSale->id,
                amount: $request->total_payable_amount,
                balance_type: 'credit'
            );

            if ($updateSale->customer_id) {

                // Update customer ledger
                $this->customerUtil->updateCustomerLedger(
                    voucher_type_id: 1,
                    customer_id: $updateSale->customer_id,
                    previous_branch_id: auth()->user()->branch_id,
                    new_branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $updateSale->id,
                    amount: $request->total_payable_amount
                );
            }
        }

        // Update sale product rows
        $__index = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : NULL;
            $warehouse_id = $request->warehouse_ids[$__index] == 'NULL' ? NULL : $request->warehouse_ids[$__index];

            $saleProduct = SaleProduct::where('sale_id', $updateSale->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->where('stock_branch_id', $request->branch_ids[$__index])
                ->where('stock_warehouse_id', $warehouse_id)
                ->first();

            if ($saleProduct) {

                $saleProduct->quantity = $request->quantities[$__index];
                $saleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$__index];
                $saleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$__index];
                $saleProduct->unit_price_inc_tax = $request->unit_prices[$__index];
                $saleProduct->unit_discount_type = $request->unit_discount_types[$__index];
                $saleProduct->unit_discount = $request->unit_discounts[$__index];
                $saleProduct->unit_discount_amount = $request->unit_discount_amounts[$__index];
                $saleProduct->unit_tax_percent = $request->unit_tax_percents[$__index];
                $saleProduct->unit_tax_amount = $request->unit_tax_amounts[$__index];
                $saleProduct->unit = $request->units[$__index];
                $saleProduct->subtotal = $request->subtotals[$__index];
                $saleProduct->description = $request->descriptions[$__index] ? $request->descriptions[$__index] : NULL;
                $saleProduct->delete_in_update = 0;
                $saleProduct->save();
            } else {

                $addSaleProduct = new SaleProduct();
                $addSaleProduct->sale_id = $updateSale->id;
                $addSaleProduct->stock_warehouse_id = $warehouse_id;
                $addSaleProduct->stock_branch_id = $request->branch_ids[$__index];
                $addSaleProduct->product_id = $product_id;
                $addSaleProduct->product_variant_id = $variant_id;
                $addSaleProduct->quantity = $request->quantities[$__index];
                $addSaleProduct->unit_discount_type = $request->unit_discount_types[$__index];
                $addSaleProduct->unit_discount = $request->unit_discounts[$__index];
                $addSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$__index];
                $addSaleProduct->unit_tax_percent = $request->unit_tax_percents[$__index];
                $addSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$__index];
                $addSaleProduct->unit = $request->units[$__index];
                $addSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$__index];
                $addSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$__index];
                $addSaleProduct->unit_price_inc_tax = $request->unit_prices[$__index];
                $addSaleProduct->subtotal = $request->subtotals[$__index];
                $addSaleProduct->description = $request->descriptions[$__index] ? $request->descriptions[$__index] : NULL;
                $addSaleProduct->save();
            }

            $__index++;
        }

        $deleteNotFoundSaleProducts = SaleProduct::with('purchaseSaleProductChains', 'purchaseSaleProductChains.purchaseProduct')
            ->where('sale_id', $updateSale->id)
            ->where('delete_in_update', 1)->get();

        foreach ($deleteNotFoundSaleProducts as $deleteNotFoundSaleProduct) {

            $storedProductId = $deleteNotFoundSaleProduct->product_id;
            $storedVariantId = $deleteNotFoundSaleProduct->product_variant_id ? $deleteNotFoundSaleProduct->product_variant_id : NULL;
            $storedStockBranchId = $deleteNotFoundSaleProduct->stock_branch_id;
            $storedStockWarehouseId = $deleteNotFoundSaleProduct->stock_warehouse_id;

            $purchaseSaleProductChains = $deleteNotFoundSaleProduct->purchaseSaleProductChains;

            $deleteNotFoundSaleProduct->delete();

            $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

            if ($storedStockWarehouseId) {

                $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $storedStockWarehouseId);
            } else {

                $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, $storedStockBranchId);
            }

            foreach ($purchaseSaleProductChains as $purchaseSaleProductChain) {

                $this->purchaseUtil->adjustPurchaseLeftQty($purchaseSaleProductChain->purchaseProduct);
            }
        }

        if ($request->status == 1) {

            $sale_products = DB::table('sale_products')->where('sale_id', $updateSale->id)->get();

            foreach ($sale_products as $saleProduct) {

                $variant_id = $saleProduct->product_variant_id ? $saleProduct->product_variant_id : NULL;

                $this->productStockUtil->adjustMainProductAndVariantStock($saleProduct->product_id, $variant_id);

                if ($saleProduct->stock_warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($saleProduct->product_id, $variant_id, $saleProduct->stock_warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($saleProduct->product_id, $variant_id, $saleProduct->stock_branch_id);
                }
            }

            $sale = Sale::with([
                'sale_products',
                'sale_products.product',
                'sale_products.purchaseSaleProductChains',
                'sale_products.purchaseSaleProductChains.purchaseProduct',
            ])->where('id', $updateSale->id)->first();

            $this->saleUtil->updatePurchaseSaleProductChain($sale, $stockAccountingMethod);
        }

        if ($request->status == 1 || $request->status == 3) {

            if ($request->paying_amount > 0) {

                $settings = DB::table('general_settings')->select(['id', 'prefix'])->first();
                $paymentInvoicePrefix = json_decode($settings->prefix, true)['sale_payment'];
                $sale = Sale::where('id', $saleId)->first();

                // Add sale payment
                $addPaymentGetId = $this->saleUtil->addPaymentGetId(
                    invoicePrefix: $paymentInvoicePrefix,
                    request: $request,
                    payingAmount: $request->paying_amount,
                    invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                    saleId: $sale->id,
                    customerPaymentId: NULL
                );

                // Add bank/cash-in-hand A/C ledger
                $this->accountUtil->addAccountLedger(
                    voucher_type_id: 10,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $addPaymentGetId,
                    amount: $request->paying_amount,
                    balance_type: 'debit'
                );

                if ($sale->customer_id) {

                    // add customer ledger
                    $this->customerUtil->addCustomerLedger(
                        voucher_type_id: 3,
                        customer_id: $sale->customer_id,
                        branch_id: auth()->user()->branch_id,
                        date: $request->date,
                        trans_id: $addPaymentGetId,
                        amount: $request->paying_amount
                    );
                }
            }

            $adjustedSale = $this->saleUtil->adjustSaleInvoiceAmounts($updateSale);

            $this->userActivityLogUtil->addLog(action: 2, subject_type: $request->status == 1 ? 7 : 8, data_obj: $adjustedSale);
        }

        if ($request->status == 1) {

            session()->flash('successMsg', 'Sale updated successfully');

                return response()->json(['finalMsg' => __('Sale updated successfully')]);

        } elseif ($request->status == 2) {

            session()->flash('successMsg', 'Sale draft created successfully');

                    return response()->json(['successMsg' => __('Sale draft updaeted successfully')]);

        } elseif ($request->status == 3) {

            session()->flash('successMsg', 'Sale order updated successfully');

                return response()->json(['successMsg' => __('Sale order updaeted successfully')]);

        } elseif ($request->status == 4) {

            session()->flash('successMsg', 'Sale quotation updated successfully');

                return response()->json(['successMsg' => __('Sales quotation updated successfully')]);

        }
    }

    // Delete Sale
    public function delete(Request $request, $saleId)
    {
        if (auth()->user()->permission->sale['delete_add_sale'] == '0') {

            return response()->json('Access Denied');
        }

        $this->saleUtil->deleteSale($request, $saleId);


            return response()->json(__('Sale deleted successfully'));


    }

    // Sale Packing Slip
    public function packingSlip($saleId)
    {
        $sale = Sale::with(['branch', 'customer'])->where('id', $saleId)->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($saleId);

        return view('sales.ajax_view.print_packing_slip', compact('sale', 'customerCopySaleProducts'));
    }

    // Shipments View
    public function shipments(Request $request)
    {
        if (auth()->user()->permission->sale['shipment_access'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->saleUtil->saleShipmentListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('sales.shipments', compact('branches'));
    }

    // Update shipment
    public function updateShipment(Request $request, $saleId)
    {
        if (auth()->user()->permission->sale['shipment_access'] == '0') {

            return response()->json('Access Denied');
        }

        $sale = Sale::where('id', $saleId)->first();
        $sale->shipment_details = $request->shipment_details;
        $sale->shipment_address = $request->shipment_address;
        $sale->shipment_status = $request->shipment_status;
        $sale->delivered_to = $request->delivered_to;
        $sale->save();

        $this->userActivityLogUtil->addLog(
            action: 2,
            subject_type: $sale->status == 1 ? 7 : 8,
            data_obj: $sale
        );


            return response()->json(__('Successfully shipment is updated.'));


    }

    // Get all customers requested by ajax
    public function getAllCustomer()
    {
        $customers = Customer::select('id', 'name',  'pay_term', 'pay_term_number', 'phone', 'total_sale_due')
            ->where('is_walk_in_customer', 0)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($customers);
    }

    // Get all user requested by ajax
    public function getAllUser()
    {
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $users = AdminAndUser::with(['role'])
                ->select(['id', 'prefix',  'name', 'last_name', 'role_type', 'role_id', 'email'])->where('allow_login', 1)->get();

            return response()->json($users);
        } else {

            $users = AdminAndUser::with(['role'])->where('branch_id', auth()->user()->branch_id)
                ->select(['id', 'prefix',  'name', 'last_name', 'role_type', 'role_id', 'email'])
                ->where('allow_login', 1)
                ->get();

            return response()->json($users);
        }
    }

    // Search product by code
    public function searchProduct($status, $product_code, $price_group_id, $warehouse_id)
    {
        $__warehouse_id = $warehouse_id == 'NULL' ? NULL : $warehouse_id;
        $product_code = (string)$product_code;
        $__product_code = str_replace('~', '/', $product_code);
        $branch_id = auth()->user()->branch_id;
        $__price_group_id = $price_group_id == 'no_id' ? NULL : $price_group_id;
        $is_allowed_discount = true;

        $product = Product::with([
            'product_variants',
            'product_variants.updateVariantCost',
            'tax:id,tax_percent',
            'unit:id,name',
            'updateProductCost'
        ])->where('product_code', $__product_code)
            ->select([
                'id',
                'name',
                'type',
                'product_code',
                'product_price',
                'profit',
                'product_cost_with_tax',
                'thumbnail_photo',
                'category_id',
                'brand_id',
                'unit_id',
                'tax_id',
                'tax_type',
                'is_show_emi_on_pos',
                'is_manage_stock',
            ])->first();

        if ($__warehouse_id) {

            return $this->nameSearchUtil->addSaleSearchStockToWarehouse($product, $__product_code, $__warehouse_id, $status, $is_allowed_discount, $__price_group_id);
        } else {

            return $this->nameSearchUtil->searchStockToBranch($product, $__product_code, $branch_id, $status, $is_allowed_discount, $__price_group_id);
        }
    }

    // Check Single product Stock
    public function checkSingleProductStock($status, $product_id, $price_group_id, $warehouse_id)
    {
        $__warehouse_id = $warehouse_id == 'NULL' ? NULL : $warehouse_id;
        $is_allowed_discount = true;

        if ($__warehouse_id) {

            return $this->nameSearchUtil->checkAddSaleWarehouseSingleProductStock($product_id, $__warehouse_id, $status, $is_allowed_discount, $price_group_id);
        } else {

            return $this->nameSearchUtil->checkBranchSingleProductStock($product_id, auth()->user()->branch_id, $status, $is_allowed_discount, $price_group_id);
        }
    }

    // Check Branch variant product Stock
    public function checkVariantProductStock($status, $product_id, $variant_id, $price_group_id, $warehouse_id)
    {
        $__warehouse_id = $warehouse_id == 'NULL' ? NULL : $warehouse_id;
        $is_allowed_discount = true;

        if ($__warehouse_id) {

            return $this->nameSearchUtil->checkAddSaleWarehouseVariantProductStock($product_id, $variant_id, $warehouse_id, $status, $is_allowed_discount, $price_group_id);
        } else {

            return $this->nameSearchUtil->checkBranchVariantProductStock($product_id, $variant_id, auth()->user()->branch_id, $status, $is_allowed_discount, $price_group_id);
        }
    }

    public function editShipment($saleId)
    {
        $sale = Sale::where('id', $saleId)->first();
        return view('sales.ajax_view.edit_shipment', compact('sale'));
    }

    public function viewPayment($saleId)
    {
        $sale = Sale::with(['customer', 'branch', 'sale_payments', 'sale_payments.paymentMethod'])
            ->where('id', $saleId)->first();
        return view('sales.ajax_view.payment_view', compact('sale'));
    }

    // Show payment modal
    public function paymentModal($saleId)
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $sale = Sale::with('branch', 'customer')->where('id', $saleId)->first();
        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();
        return view('sales.ajax_view.add_payment', compact('sale', 'accounts', 'methods'));
    }

    public function paymentAdd(Request $request, $saleId)
    {
        if (auth()->user()->permission->sale['sale_payment'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        if ($request->paying_amount > 0) {

            $settings = DB::table('general_settings')->select(['id', 'prefix'])->first();
            $paymentInvoicePrefix = json_decode($settings->prefix, true)['sale_payment'];
            $sale = Sale::where('id', $saleId)->first();

            // Add sale payment
            $addPaymentGetId = $this->saleUtil->addPaymentGetId(
                invoicePrefix: $paymentInvoicePrefix,
                request: $request,
                payingAmount: $request->paying_amount,
                invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                saleId: $sale->id,
                customerPaymentId: NULL
            );

            // Add bank/cash-in-hand A/C ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 10,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $addPaymentGetId,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            if ($sale->customer_id) {

                // add customer ledger
                $this->customerUtil->addCustomerLedger(
                    voucher_type_id: 3,
                    customer_id: $sale->customer_id,
                    branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $addPaymentGetId,
                    amount: $request->paying_amount
                );
            }

            $salePayment = DB::table('sale_payments')
                ->where('sale_payments.id', $addPaymentGetId)
                ->leftJoin('customers', 'sale_payments.customer_id', 'customers.id')
                ->leftJoin('payment_methods', 'sale_payments.payment_method_id', 'payment_methods.id')
                ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
                ->select(
                    'sale_payments.invoice_id as voucher_no',
                    'sale_payments.date',
                    'sale_payments.paid_amount',
                    'customers.name as customer',
                    'customers.phone',
                    'payment_methods.name as method',
                    'sales.invoice_id as ags',
                )->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 27, data_obj: $salePayment);

            $this->saleUtil->adjustSaleInvoiceAmounts($sale);
        }


            return response()->json(__('Payment added successfully.'));


    }

    // Show payment modal
    public function paymentEdit($paymentId)
    {
        if (auth()->user()->permission->sale['sale_payment'] == '0') {

            return response()->json('Access Denied');
        }

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $payment = SalePayment::with('sale', 'sale.customer', 'sale.branch')->where('id', $paymentId)->first();
        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();
        return view('sales.ajax_view.edit_payment', compact('payment', 'accounts', 'methods'));
    }

    // Payment update
    public function paymentUpdate(Request $request, $paymentId)
    {
        if (auth()->user()->permission->sale['sale_payment'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $payment = SalePayment::with(['sale'])->where('id', $paymentId)->first();
        $this->saleUtil->updatePayment($request, $payment);

        if ($payment->customer_payment_id == NULL) {

            // Update Bank/Cash-In-Hand ledger
            $this->accountUtil->updateAccountLedger(
                voucher_type_id: 10,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $payment->id,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            $this->saleUtil->adjustSaleInvoiceAmounts($payment->sale);

            if ($payment->sale->customer_id) {

                // Update customer ledger
                $this->customerUtil->updateCustomerLedger(
                    voucher_type_id: 3,
                    customer_id: $payment->sale->customer_id,
                    previous_branch_id: auth()->user()->branch_id,
                    new_branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $payment->id,
                    amount: $request->paying_amount
                );
            }
        }

        return response()->json(__('Payment updated successfully.'));
    }

    // Show payment modal
    public function returnPaymentModal($saleId)
    {
        if (auth()->user()->permission->sale['sale_payment'] == '0') {

            return response()->json('Access Denied');
        }

        $sale = Sale::with('branch', 'customer')->where('id', $saleId)->first();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        return view('sales.ajax_view.add_return_payment', compact('sale', 'accounts', 'methods'));
    }

    public function returnPaymentAdd(Request $request, $saleId)
    {
        if (auth()->user()->permission->sale['sale_payment'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        if ($request->paying_amount > 0) {

            $sale = Sale::with(['sale_return'])->where('id', $saleId)->first();

            $saleReturnPaymentGetId = $this->saleUtil->saleReturnPaymentGetId(
                request: $request,
                sale: $sale,
                customer_payment_id: NULL,
                sale_return_id: $sale->sale_return ? $sale->sale_return->id : NULL
            );

            // Add bank A/C ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 12,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $saleReturnPaymentGetId,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            if ($sale) {

                $this->saleUtil->adjustSaleInvoiceAmounts($sale);
            }

            // Update sale return
            if ($sale->sale_return) {

                $this->saleUtil->adjustSaleReturnAmounts($sale->sale_return);
            }

            if ($sale->customer_id) {

                // add customer ledger
                $this->customerUtil->addCustomerLedger(
                    voucher_type_id: 4,
                    customer_id: $sale->customer_id,
                    branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $saleReturnPaymentGetId,
                    amount: $request->paying_amount
                );
            }
        }

        return response()->json('Return amount paid successfully.');
    }

    public function returnPaymentEdit($paymentId)
    {
        $payment = SalePayment::with([
            'sale',
            'sale.branch',
            'sale_return',
            'sale_return.branch',
            'customer'
        ])->where('id', $paymentId)->first();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();
        return view('sales.ajax_view.edit_return_payment', compact('payment', 'accounts', 'methods'));
    }

    public function returnPaymentUpdate(Request $request, $paymentId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $updateSalePayment = SalePayment::with(
            'account',
            'customer',
            'sale',
            'sale_return',
        )->where('id', $paymentId)->first();

        $this->saleUtil->updateSaleReturnPayment($request, $updateSalePayment);

        // Update sale return
        if ($updateSalePayment->sale_return) {

            $this->saleUtil->adjustSaleReturnAmounts($updateSalePayment->sale_return);
        }

        if ($updateSalePayment->customer_payment_id == NULL) {

            // Update Bank/Cash-in-Hand A/C ledger
            $this->accountUtil->updateAccountLedger(
                voucher_type_id: 12,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $updateSalePayment->id,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            if ($updateSalePayment->customer_id) {

                // Update customer ledger
                $this->customerUtil->updateCustomerLedger(
                    voucher_type_id: 4,
                    customer_id: $updateSalePayment->customer_id,
                    previous_branch_id: auth()->user()->branch_id,
                    new_branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $updateSalePayment->id,
                    amount: $request->paying_amount
                );
            }
        }

        if ($updateSalePayment->sale) {

            $this->saleUtil->adjustSaleInvoiceAmounts($updateSalePayment->sale);
        }

        return response()->json(__('Payment updated successfully.'));
    }

    // payment details
    public function paymentDetails($paymentId)
    {
        if (auth()->user()->permission->sale['sale_payment'] == '0') {

            return response()->json('Access Denied');
        }

        $payment = SalePayment::with('sale', 'sale.branch', 'sale.customer', 'paymentMethod')->where('id', $paymentId)->first();
        return view('sales.ajax_view.payment_details', compact('payment'));
    }

    // Delete sale payment
    public function paymentDelete(Request $request, $paymentId)
    {
        if (auth()->user()->permission->sale['sale_payment'] == '0') {

            return response()->json('Access Denied');
        }

        $deleteSalePayment = SalePayment::with('account', 'customer', 'sale', 'sale.sale_return')
            ->where('id', $paymentId)->first();

        if (!is_null($deleteSalePayment)) {

            //Update customer due
            if ($deleteSalePayment->payment_type == 1) {
                // Update sale
                $storedCustomerId = $deleteSalePayment->sale->customer_id;

                $storedSale = $deleteSalePayment->sale;

                $storedAccountId = $deleteSalePayment->account_id;

                if ($deleteSalePayment->attachment != null) {

                    if (file_exists(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment))) {

                        unlink(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment));
                    }
                }

                $salePayment = DB::table('sale_payments')
                    ->where('sale_payments.id', $paymentId)
                    ->leftJoin('customers', 'sale_payments.customer_id', 'customers.id')
                    ->leftJoin('payment_methods', 'sale_payments.payment_method_id', 'payment_methods.id')
                    ->leftJoin('sales', 'sale_payments.sale_id', 'sales.id')
                    ->select(
                        'sale_payments.invoice_id as voucher_no',
                        'sale_payments.date',
                        'sale_payments.paid_amount',
                        'customers.name as customer',
                        'customers.phone',
                        'payment_methods.name as method',
                        'sales.invoice_id as ags',
                    )->first();

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 27, data_obj: $salePayment);

                $deleteSalePayment->delete();

                $this->saleUtil->adjustSaleInvoiceAmounts($storedSale);

                if ($storedCustomerId) {

                    $this->customerUtil->adjustCustomerAmountForSalePaymentDue($storedCustomerId);
                }

                if ($storedAccountId) {

                    $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
                }
            } elseif ($deleteSalePayment->payment_type == 2) {

                $storedCustomerId = $deleteSalePayment->customer_id;
                $storedSale = $deleteSalePayment->sale;
                $storedSaleReturn = $deleteSalePayment->sale_return;
                $storedAccountId = $deleteSalePayment->account_id;

                if ($deleteSalePayment->attachment != null) {

                    if (file_exists(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment))) {

                        unlink(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment));
                    }
                }

                $deleteSalePayment->delete();

                if ($storedSale) {

                    $this->saleUtil->adjustSaleInvoiceAmounts($storedSale);
                }

                if ($storedCustomerId) {

                    $this->customerUtil->adjustCustomerAmountForSalePaymentDue($storedCustomerId);
                }

                // Update sale return
                if ($storedSaleReturn) {

                    $this->saleUtil->adjustSaleReturnAmounts($storedSaleReturn);
                }

                if ($storedAccountId) {

                    $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
                }
            }
        }
        return response()->json(__('Payment deleted successfully.'));
    }

    // Add product modal view with data
    public function addProductModalVeiw()
    {
        $units = DB::table('units')->select('id', 'name')->get();
        $warranties =  DB::table('warranties')->select('id', 'name', 'type')->get();
        $taxes = DB::table('taxes')->select(['id', 'tax_name', 'tax_percent'])->get();
        $categories = DB::table('categories')->where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
        $brands = DB::table('brands')->select('id', 'name')->get();
        return view('sales.ajax_view.add_product_modal_view', compact('units', 'warranties', 'taxes', 'categories', 'brands'));
    }

    public function getAllSubCategory($categoryId)
    {
        $sub_categories = DB::table('categories')->where('parent_category_id', $categoryId)->get();
        return response()->json($sub_categories);
    }

    public function addProduct(Request $request)
    {
        return $this->util->addQuickProductFromAddSale($request);
    }

    // Get recent added product which has been added from pos
    public function getRecentProduct($product_id)
    {
        $branch_id = auth()->user()->branch_id;
        $product = ProductBranch::with(['product', 'product.tax', 'product.unit'])
            ->where('branch_id', $branch_id)
            ->where('product_id', $product_id)
            ->first();

        if ($product->product_quantity > 0) {

            return view('sales.ajax_view.recent_product_view', compact('product'));
        } else {

            return response()->json([
                'errorMsg' => 'Product is not added in the sale table, cause you did not add any number of opening stock in this branch.'
            ]);
        }
    }

    // Get sale for printing
    public function print($saleId)
    {
        $sale = Sale::with([
            'customer',
            'branch',
            'branch.add_sale_invoice_layout',
            'branch.pos_sale_invoice_layout',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'admin'
        ])->where('id', $saleId)->first();

        $previous_due = 0;
        $total_payable_amount = $sale->total_payable_amount;
        $paying_amount = $sale->paid;
        $total_due = $sale->due;
        $change_amount = 0;

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($saleId);

        if ($sale->status == 1) {

            if ($sale->created_by == 1) {

                return view('sales.save_and_print_template.sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount',
                    'customerCopySaleProducts'
                ));
            } else {

                return view('sales.save_and_print_template.pos_sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount'
                ));
            }
        } elseif ($sale->status == 2) {

            return view('sales.save_and_print_template.draft_print', compact('sale', 'customerCopySaleProducts'));
        } elseif ($sale->status == 4) {

            return view('sales.save_and_print_template.quotation_print', compact('sale', 'customerCopySaleProducts'));
        }
    }

    // Get product price group
    public function getProductPriceGroup()
    {
        return DB::table('price_group_products')->get(['id', 'price_group_id', 'product_id', 'variant_id', 'price']);
    }

    // Get notification form method
    public function getNotificationForm($saleId)
    {
    }

    // Get notification form method
    public function settings()
    {
        if (
            !isset(auth()->user()->permission->sale['add_sale_settings']) ||
            auth()->user()->permission->sale['add_sale_settings'] == '0'
        ) {

            abort(403, 'Access Forbidden.');
        }

        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
        $price_groups = DB::table('price_groups')->where('status', 'Active')->get();

        return view('sales.settings.index', compact('taxes', 'price_groups'));
    }

    // Add tax settings
    public function settingsStore(Request $request)
    {
        if (
            !isset(auth()->user()->permission->sale['add_sale_settings']) ||
            auth()->user()->permission->sale['add_sale_settings'] == '0'
        ) {

            return response()->json('Asses Forbidden.');
        }

        $updateSaleSettings = General_setting::first();
        $saleSettings = [
            'default_sale_discount' => $request->default_sale_discount,
            'default_tax_id' => $request->default_tax_id,
            'sales_cmsn_agnt' => $request->sales_cmsn_agnt,
            'default_price_group_id' => $request->default_price_group_id,
        ];

        $updateSaleSettings->sale = json_encode($saleSettings);
        $updateSaleSettings->save();

        return response()->json(__('Sale settings updated successfully'));

    }

    public function createInvoice(Request $request)
    {
        $line_item = $request->input('line_item');
        $egs_unit = $request->input('egs_unit');
        $invoice = $request->input('invoice');

        $egs = new EGS($egsUnit);

        $egs->production = false;

        // New Keys & CSR for the EGS
        list($private_key, $csr) = $egs->generateNewKeysAndCSR('solution_name');

        // Issue a new compliance cert for the EGS
        list($request_id, $binary_security_token, $secret) = $egs->issueComplianceCertificate('123345', $csr);

        // Sign invoice
        list($signed_invoice_string, $invoice_hash, $qr) = $egs->signInvoice($invoice, $egs_unit, $binary_security_token, $private_key);

        // Check invoice compliance
        $compliance = $egs->checkInvoiceCompliance($signed_invoice_string, $invoice_hash, $binary_security_token, $secret);

        return response()->json($compliance);
    }
}
