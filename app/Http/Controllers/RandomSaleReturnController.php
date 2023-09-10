<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Utils\SaleUtil;
use App\Models\SaleReturn;
use App\Utils\AccountUtil;
use App\Utils\CustomerUtil;
use App\Utils\PurchaseUtil;
use Illuminate\Http\Request;
use App\Utils\NameSearchUtil;
use App\Utils\ProductStockUtil;
use App\Models\SaleReturnProduct;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Utils\PurchaseSaleChainUtil;
use App\Utils\InvoiceVoucherRefIdUtil;

class RandomSaleReturnController extends Controller
{
    protected $productStockUtil;
    protected $saleUtil;
    protected $nameSearchUtil;
    protected $accountUtil;
    protected $customerUtil;
    protected $invoiceVoucherRefIdUtil;
    protected $purchaseUtil;
    protected $userActivityLogUtil;
    protected $purchaseSaleChainUtil;

    public function __construct(
        ProductStockUtil $productStockUtil,
        SaleUtil $saleUtil,
        NameSearchUtil $nameSearchUtil,
        AccountUtil $accountUtil,
        CustomerUtil $customerUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        PurchaseUtil $purchaseUtil,
        UserActivityLogUtil $userActivityLogUtil,
        PurchaseSaleChainUtil $purchaseSaleChainUtil,
    ) {

        $this->productStockUtil = $productStockUtil;
        $this->saleUtil = $saleUtil;
        $this->nameSearchUtil = $nameSearchUtil;
        $this->accountUtil = $accountUtil;
        $this->customerUtil = $customerUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->purchaseSaleChainUtil = $purchaseSaleChainUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function create()
    {
        $customers = DB::table('customers')
            ->where('status', 1)->select('id', 'name', 'phone')
            ->orderBy('id', 'desc')->get();

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $saleReturnAccounts = DB::table('account_branches')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('accounts.account_type', 6)
            ->get(['accounts.id', 'accounts.name']);

        $taxes = DB::table('taxes')->get(['id', 'tax_name', 'tax_percent']);

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        return view('sales.sale_return.random_return.create', compact('customers', 'methods', 'accounts', 'saleReturnAccounts', 'taxes', 'price_groups'));
    }

    // Search product by code
    public function searchProduct($product_code)
    {
        $product_code = (string)$product_code;
        $__product_code = str_replace('~', '/', $product_code);
        $branch_id = auth()->user()->branch_id;

        $product = Product::with([
            'product_variants',
            'tax:id,tax_percent',
            'unit:id,name',
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

        return $this->nameSearchUtil->searchStockToBranch($product, $__product_code, $branch_id, 2);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'sale_return_account_id' => 'required',
        ], [
            'sale_return_account_id.required' => 'Sale Return A/C is required',
        ]);

        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly

            $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
            $invoicePrefix = json_decode($prefixSettings->prefix, true)['sale_return'];

            $sale = Sale::where('id', $request->sale_id)->first();

            $invoiceId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('sale_returns'), 4, "0", STR_PAD_LEFT);

            $addSaleReturn = new SaleReturn();
            $addSaleReturn->total_item = $request->total_item;
            $addSaleReturn->total_qty = $request->total_qty;
            $addSaleReturn->sale_id = $request->sale_id;
            $addSaleReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . $invoiceId;
            $addSaleReturn->customer_id = $request->customer_id;
            $addSaleReturn->branch_id = $sale ? $sale->branch_id : auth()->user()->branch_id;
            $addSaleReturn->sale_return_account_id = $request->sale_return_account_id;
            $addSaleReturn->admin_id = auth()->user()->id;
            $addSaleReturn->return_discount_type = $request->return_discount_type;
            $addSaleReturn->return_discount = $request->return_discount;
            $addSaleReturn->return_discount_amount = $request->return_discount_amount;
            $addSaleReturn->return_tax = $request->return_tax;
            $addSaleReturn->return_tax_amount = $request->return_tax_amount;
            $addSaleReturn->net_total_amount = $request->net_total_amount;
            $addSaleReturn->total_return_amount = $request->total_return_amount;
            $addSaleReturn->total_return_due_pay = $request->paying_amount;
            $addSaleReturn->total_return_due = $request->total_return_amount - $request->paying_amount;
            $addSaleReturn->date = $request->date;
            $addSaleReturn->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
            $addSaleReturn->month = date('F');
            $addSaleReturn->year = date('Y');
            $addSaleReturn->save();

            // Add sale return products
            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;
                $addReturnProduct = new SaleReturnProduct();
                $addReturnProduct->sale_return_id = $addSaleReturn->id;
                $addReturnProduct->sale_product_id = $request->sale_product_ids[$index];
                $addReturnProduct->product_id = $product_id;
                $addReturnProduct->product_variant_id = $variant_id;
                $addReturnProduct->tax_type = $request->tax_types[$index];
                $addReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
                $addReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
                $addReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
                $addReturnProduct->unit_discount = $request->unit_discounts[$index];
                $addReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
                $addReturnProduct->return_qty = $request->return_quantities[$index];
                $addReturnProduct->unit = $request->units[$index];
                $addReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $addReturnProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
                $addReturnProduct->unit_price_inc_tax = $request->unit_prices[$index];
                $addReturnProduct->return_subtotal = $request->subtotals[$index];
                $addReturnProduct->save();

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'sale_return_product_id',
                    transId: $addReturnProduct->id,
                    branchId: auth()->user()->branch_id,
                    productId: $product_id,
                    quantity: $request->return_quantities[$index],
                    variantId: $variant_id,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    sellingPrice: $request->unit_prices_exc_tax[$index],
                    subTotal: $request->subtotals[$index],
                    createdAt: date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))),
                );

                $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);
                $this->productStockUtil->adjustBranchStock($product_id, $variant_id, auth()->user()->branch_id);

                $index++;
            }

            // Add Sale Return A/C ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 2,
                date: $request->date,
                account_id: $request->sale_return_account_id,
                trans_id: $addSaleReturn->id,
                amount: $request->total_return_amount,
                balance_type: 'debit'
            );

            if ($request->customer_id) {

                $this->customerUtil->addCustomerLedger(
                    voucher_type_id: 2,
                    customer_id: $request->customer_id,
                    branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $addSaleReturn->id,
                    amount: $request->total_return_amount
                );
            }

            if ($request->paying_amount > 0) {

                $saleReturnPaymentGetId = $this->saleUtil->saleReturnPaymentGetId(
                    request: $request,
                    sale: $sale,
                    customer_payment_id: NULL,
                    sale_return_id: $addSaleReturn->id
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

                if ($request->customer_id) {

                    // add customer ledger
                    $this->customerUtil->addCustomerLedger(
                        voucher_type_id: 4,
                        customer_id: $request->customer_id,
                        branch_id: auth()->user()->branch_id,
                        date: $request->date,
                        trans_id: $saleReturnPaymentGetId,
                        amount: $request->paying_amount
                    );
                }
            }

            if ($sale) {

                $sale->is_return_available = 1;

                $this->saleUtil->adjustSaleInvoiceAmounts($sale);
            }

            $saleReturn = SaleReturn::with([
                'customer',
                'branch',
                'sale_return_products',
                'sale_return_products.product',
                'sale_return_products.variant',
            ])->where('id', $addSaleReturn->id)->first();

            $this->userActivityLogUtil->addLog(
                action: 1,
                subject_type: 9,
                data_obj: $saleReturn
            );


            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('sales.sale_return.save_and_print_template.sale_return_print_view', compact('saleReturn'));
        } else {


                return response()->json(['successMsg' => __('Sale Return is created successfully.')]);


        }
    }

    public function edit($returnId)
    {
        $return = SaleReturn::with([
            'sale',
            'sale.sale_products',
            'sale.sale_products.product',
            'sale.sale_products.variant',
            'branch',
            'sale_return_products',
            'sale_return_products.product',
            'sale_return_products.variant',
        ])->where('id', $returnId)->first();

        $customers = DB::table('customers')
            ->where('status', 1)->select('id', 'name', 'phone')
            ->orderBy('id', 'desc')->get();

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $saleReturnAccounts = DB::table('account_branches')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('accounts.account_type', 6)
            ->get(['accounts.id', 'accounts.name']);

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        $taxes = DB::table('taxes')->get(['id', 'tax_name', 'tax_percent']);

        $branch_id = $return->branch_id ? $return->branch_id : 'NULL';

        $branchWiseCustomerAmountUtil = new \App\Utils\BranchWiseCustomerAmountUtil();
        $customerBalance = $branchWiseCustomerAmountUtil->branchWiseCustomerAmount($return->customer_id, $branch_id)['total_sale_due'];

        return view('sales.sale_return.random_return.edit', compact('return', 'customers', 'methods', 'accounts', 'saleReturnAccounts', 'price_groups', 'taxes', 'customerBalance'));
    }

    public function update(Request $request, $returnId)
    {
        $this->validate($request, [
            'date' => 'required',
            'sale_return_account_id' => 'required',
        ], [
            'sale_return_account_id.required' => 'Sale Return A/C is required',
        ]);

        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['sale_return'];

        $sale = Sale::where('id', $request->sale_id)->first();

        $invoiceId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('sale_returns'), 4, "0", STR_PAD_LEFT);

        $updateSaleReturn = SaleReturn::with(['sale', 'sale_return_products'])->where('id', $returnId)->first();

        foreach ($updateSaleReturn->sale_return_products as $return_product) {

            $return_product->is_delete_in_update = 1;
            $return_product->save();
        }

        $updateSaleReturn->total_item = $request->total_item;
        $updateSaleReturn->total_qty = $request->total_qty;
        $updateSaleReturn->sale_id = $request->sale_id;
        $updateSaleReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . $invoiceId;
        $updateSaleReturn->customer_id = $request->customer_id;
        $updateSaleReturn->branch_id = $sale ? $sale->branch_id : auth()->user()->branch_id;
        $updateSaleReturn->sale_return_account_id = $request->sale_return_account_id;
        $updateSaleReturn->admin_id = auth()->user()->id;
        $updateSaleReturn->return_discount_type = $request->return_discount_type;
        $updateSaleReturn->return_discount = $request->return_discount;
        $updateSaleReturn->return_discount_amount = $request->return_discount_amount;
        $updateSaleReturn->return_tax = $request->return_tax;
        $updateSaleReturn->return_tax_amount = $request->return_tax_amount;
        $updateSaleReturn->net_total_amount = $request->net_total_amount;
        $updateSaleReturn->total_return_amount = $request->total_return_amount;
        $updateSaleReturn->total_return_due_pay = $request->paying_amount;
        $updateSaleReturn->date = $request->date;
        $updateSaleReturn->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $updateSaleReturn->month = date('F');
        $updateSaleReturn->year = date('Y');
        $updateSaleReturn->save();

        // update sale return products
        $index = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;

            $saleReturnProduct = SaleReturnProduct::with('purchaseProduct')
                ->where('sale_return_id', $returnId)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($saleReturnProduct) {

                $saleReturnProduct->return_qty = $request->return_quantities[$index];
                $saleReturnProduct->unit = $request->units[$index];
                $saleReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $saleReturnProduct->tax_type = $request->tax_types[$index];
                $saleReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
                $saleReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
                $saleReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
                $saleReturnProduct->unit_discount = $request->unit_discounts[$index];
                $saleReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
                $saleReturnProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
                $saleReturnProduct->unit_price_inc_tax = $request->unit_prices[$index];
                $saleReturnProduct->return_subtotal = $request->subtotals[$index];
                $saleReturnProduct->is_delete_in_update = 0;
                $saleReturnProduct->save();

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'sale_return_product_id',
                    transId: $saleReturnProduct->id,
                    branchId: auth()->user()->branch_id,
                    productId: $product_id,
                    quantity: $request->return_quantities[$index],
                    variantId: $variant_id,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    sellingPrice: $request->unit_prices_exc_tax[$index],
                    subTotal: $request->subtotals[$index],
                    createdAt: date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))),
                );
            } else {

                $addReturnProduct = new SaleReturnProduct();
                $addReturnProduct->sale_return_id = $updateSaleReturn->id;
                $addReturnProduct->sale_product_id = $request->sale_product_ids[$index];
                $addReturnProduct->product_id = $product_id;
                $addReturnProduct->product_variant_id = $variant_id;
                $addReturnProduct->return_qty = $request->return_quantities[$index];
                $addReturnProduct->unit = $request->units[$index];
                $addReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $addReturnProduct->tax_type = $request->tax_types[$index];
                $addReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
                $addReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
                $addReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
                $addReturnProduct->unit_discount = $request->unit_discounts[$index];
                $addReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
                $addReturnProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
                $addReturnProduct->unit_price_inc_tax = $request->unit_prices[$index];
                $addReturnProduct->return_subtotal = $request->subtotals[$index];
                $addReturnProduct->save();

                $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                    tranColName: 'sale_return_product_id',
                    transId: $addReturnProduct->id,
                    branchId: auth()->user()->branch_id,
                    productId: $product_id,
                    quantity: $request->return_quantities[$index],
                    variantId: $variant_id,
                    unitCostIncTax: $request->unit_costs_inc_tax[$index],
                    sellingPrice: $request->unit_prices_exc_tax[$index],
                    subTotal: $request->subtotals[$index],
                    createdAt: date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))),
                );
            }

            $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);
            $this->productStockUtil->adjustBranchStock($product_id, $variant_id, auth()->user()->branch_id);

            $index++;
        }

        $deleteUnusedReturnProducts = SaleReturnProduct::where('sale_return_id', $returnId)
            ->where('is_delete_in_update', 1)->get();

        foreach ($deleteUnusedReturnProducts as $deleteUnusedReturnProduct) {

            $storedProductId = $deleteUnusedReturnProduct->product_id;
            $storedVariantId = $deleteUnusedReturnProduct->product_variant_id;
            $deleteUnusedReturnProduct->delete();

            $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);
            $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, auth()->user()->branch_id);
        }

        // Add Sale Return A/C ledger
        $this->accountUtil->updateAccountLedger(
            voucher_type_id: 2,
            date: $request->date,
            account_id: $request->sale_return_account_id,
            trans_id: $updateSaleReturn->id,
            amount: $request->total_return_amount,
            balance_type: 'debit'
        );

        if ($request->customer_id) {

            $this->customerUtil->updateCustomerLedger(
                voucher_type_id: 2,
                customer_id: $request->customer_id,
                previous_branch_id: auth()->user()->branch_id,
                new_branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $updateSaleReturn->id,
                amount: $request->total_return_amount
            );
        }

        if ($request->paying_amount > 0) {

            $saleReturnPaymentGetId = $this->saleUtil->saleReturnPaymentGetId(
                request: $request,
                sale: $sale,
                customer_payment_id: NULL,
                sale_return_id: $updateSaleReturn->id
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

            if ($request->customer_id) {

                // add customer ledger
                $this->customerUtil->addCustomerLedger(
                    voucher_type_id: 4,
                    customer_id: $request->customer_id,
                    branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $saleReturnPaymentGetId,
                    amount: $request->paying_amount
                );
            }
        }

        if ($updateSaleReturn->sale) {

            $this->saleUtil->adjustSaleInvoiceAmounts($updateSaleReturn->sale);
        }

        $this->saleUtil->adjustSaleReturnAmounts($updateSaleReturn);

        $this->userActivityLogUtil->addLog(
            action: 2,
            subject_type: 9,
            data_obj: $updateSaleReturn
        );

        return response()->json(['successMsg' => __('Sale Return is updated successfully.')]);
    }
}
