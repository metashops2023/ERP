<?php

namespace App\Http\Controllers;

// use DB;
use App\Utils\Util;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Utils\AccountUtil;
use App\Utils\PurchaseUtil;
use App\Utils\SupplierUtil;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Utils\NameSearchUtil;
use App\Models\ProductVariant;
use App\Models\PurchaseReturn;
use App\Models\General_setting;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use App\Models\SupplierProduct;
use App\Utils\ProductStockUtil;
use App\Utils\PurchaseReturnUtil;
use App\Utils\UserActivityLogUtil;
use App\Models\PurchaseOrderProduct;
use App\Utils\InvoiceVoucherRefIdUtil;
use Exception;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    protected $purchaseUtil;
    protected $nameSearchUtil;
    protected $util;
    protected $supplierUtil;
    protected $productStockUtil;
    protected $accountUtil;
    protected $invoiceVoucherRefIdUtil;
    protected $purchaseReturnUtil;
    protected $userActivityLogUtil;
    public function __construct(
        NameSearchUtil $nameSearchUtil,
        PurchaseUtil $purchaseUtil,
        Util $util,
        SupplierUtil $supplierUtil,
        ProductStockUtil $productStockUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        PurchaseReturnUtil $purchaseReturnUtil,
        UserActivityLogUtil $userActivityLogUtil
    ) {
        $this->nameSearchUtil = $nameSearchUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->util = $util;
        $this->supplierUtil = $supplierUtil;
        $this->productStockUtil = $productStockUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->purchaseReturnUtil = $purchaseReturnUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index_v2(Request $request)
    {
        if (auth()->user()->permission->purchase['purchase_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseUtil->purchaseListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone')->get();
        return view('purchases.index_v2', compact('branches', 'suppliers'));
    }

    public function purchaseProductList(Request $request)
    {
        if (auth()->user()->permission->purchase['purchase_all'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseUtil->purchaseProductListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->get(['id', 'name', 'phone']);
        $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
        return view('purchases.purchase_product_list', compact('branches', 'suppliers', 'categories'));
    }

    public function poList(Request $request)
    {
        if (auth()->user()->permission->purchase['purchase_all'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->purchaseUtil->poListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $suppliers = DB::table('suppliers')->get(['id', 'name', 'phone']);
        return view('purchases.po_list', compact('branches', 'suppliers'));
    }

    // show purchase details
    public function show($purchaseId)
    {
        $purchase = Purchase::with([
            'warehouse',
            'branch',
            'supplier',
            'admin',
            'purchase_products',
            'purchase_products.product',
            'purchase_products.product.warranty',
            'purchase_products.variant',
            'purchase_payments',
        ])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.purchase_details_modal', compact('purchase'));
    }

    public function showOrder($purchaseId)
    {
        $purchase = Purchase::with([
            'warehouse',
            'branch',
            'supplier',
            'admin',
            'purchase_order_products',
            'purchase_order_products.receives',
            'purchase_products.product',
            'purchase_products.product.warranty',
            'purchase_products.variant',
            'purchase_payments',
        ])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.order_details', compact('purchase'));
    }

    public function printSupplierCopy($purchaseId)
    {
        $purchase = Purchase::with([
            'branch',
            'supplier',
            'admin',
            'purchase_order_products',
            'purchase_products.product',
            'purchase_products.variant',
        ])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.print_supplier_copy', compact('purchase'));
    }

    public function create()
    {
        if (auth()->user()->permission->purchase['purchase_add'] == '0') {

            abort(403, 'Access Forbidden.');
        }

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

        $purchaseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 3)
            ->get(['accounts.id', 'accounts.name']);

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        return view('purchases.create', compact('warehouses', 'methods', 'accounts', 'purchaseAccounts'));
    }

    // add purchase method
    public function store(Request $request)
    {
        $this->validate($request, [
            'supplier_id' => 'required',
            'invoice_id' => 'sometimes|unique:purchases,invoice_id',
            'date' => 'required|date',
            'order_discount_type' => 'required',
            'payment_method_id' => 'required',
            'purchase_account_id' => 'required',
            'account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/C is required.',
            'account_id.required' => 'Credit field must not be is empty.',
            'payment_method_id.required' => 'Payment method field is required.',
            'supplier_id.required' => 'Supplier is required.',
        ]);

        if (isset($request->warehouse_count)) {

            $this->validate($request, ['warehouse_id' => 'required']);
        }

        try {

            DB::beginTransaction();
            $prefixSettings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
            $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_invoice'];
            $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];
            $isEditProductPrice = json_decode($prefixSettings->purchase, true)['is_edit_pro_price'];

            $this->validate($request, ['supplier_id' => 'required']);

            if (!isset($request->product_ids)) {

                return response()->json(['errorMsg' => __('Product table is empty.')]);
            } elseif (count($request->product_ids) > 60) {

                return response()->json(['errorMsg' => __('Purchase invoice items must be less than 60 or equal.')]);
            }

            $product_ids = $request->product_ids;
            $variant_ids = $request->variant_ids;
            $quantities = $request->quantities;
            $unit_costs_with_discount = $request->unit_costs_with_discount;
            $net_unit_costs = $request->net_unit_costs;
            $profits = $request->profits;
            $selling_prices = $request->selling_prices;

            // Add supplier product
            $i = 0;
            foreach ($product_ids as $product_id) {

                $variant_id = $variant_ids[$i] != 'noid' ? $variant_ids[$i] : NULL;
                $SupplierProduct = SupplierProduct::where('supplier_id', $request->supplier_id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->first();

                if (!$SupplierProduct) {

                    $addSupplierProduct = new SupplierProduct();
                    $addSupplierProduct->supplier_id = $request->supplier_id;
                    $addSupplierProduct->product_id = $product_id;
                    $addSupplierProduct->product_variant_id = $variant_id;
                    $addSupplierProduct->label_qty = $quantities[$i];
                    $addSupplierProduct->save();
                } else {

                    $SupplierProduct->label_qty = $SupplierProduct->label_qty + $quantities[$i];
                    $SupplierProduct->save();
                }
                $i++;
            }

            $updateLastCreated = Purchase::where('is_last_created', 1)
                ->where('branch_id', auth()->user()->branch_id)
                ->select('id', 'is_last_created')
                ->first();

            if ($updateLastCreated) {

                $updateLastCreated->is_last_created = 0;
                $updateLastCreated->save();
            }

            $__invoicePrefix = '';
            if ($request->purchase_status == 1) {

                $__invoicePrefix = $invoicePrefix != null ? $invoicePrefix : '';
            } elseif ($request->purchase_status == 3) {

                $__invoicePrefix = 'PO';
            }

            // add purchase total information
            $addPurchase = new Purchase();
            $addPurchase->invoice_id = $request->invoice_id ? $request->invoice_id : $__invoicePrefix . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchases'), 5, "0", STR_PAD_LEFT);
            $addPurchase->warehouse_id = $request->warehouse_id ? $request->warehouse_id : NULL;
            $addPurchase->branch_id = auth()->user()->branch_id;
            $addPurchase->supplier_id = $request->supplier_id;
            $addPurchase->purchase_account_id = $request->purchase_account_id;
            $addPurchase->pay_term = $request->pay_term;
            $addPurchase->pay_term_number = $request->pay_term_number;
            $addPurchase->admin_id = auth()->user()->id;
            $addPurchase->total_item = $request->total_item;
            $addPurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
            $addPurchase->order_discount_type = $request->order_discount_type;
            $addPurchase->order_discount_amount = $request->order_discount_amount;
            $addPurchase->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0.00;
            $addPurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
            $addPurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
            $addPurchase->net_total_amount = $request->net_total_amount;
            $addPurchase->total_purchase_amount = $request->total_purchase_amount;
            $addPurchase->paid = $request->paying_amount;
            $addPurchase->due = $request->purchase_due;
            $addPurchase->shipment_details = $request->shipment_details;
            $addPurchase->purchase_note = $request->purchase_note;
            $addPurchase->purchase_status = $request->purchase_status;
            $addPurchase->is_purchased = $request->purchase_status == 1 ? 1 : 0;
            $addPurchase->po_qty = $request->purchase_status == 3 ? $request->total_qty : 0;
            $addPurchase->po_pending_qty = $request->purchase_status == 3 ? $request->total_qty : 0;
            $addPurchase->po_receiving_status = $request->purchase_status == 1 ? NULL : 'Pending';
            $addPurchase->date = $request->date;
            $addPurchase->delivery_date = $request->delivery_date;
            $addPurchase->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
            $addPurchase->time = date('h:i:s a');
            $addPurchase->month = date('F');
            $addPurchase->year = date('Y');
            $addPurchase->is_last_created = 1;

            if ($request->hasFile('attachment')) {

                $purchaseAttachment = $request->file('attachment');
                $purchaseAttachmentName = uniqid() . '-' . '.' . $purchaseAttachment->getClientOriginalExtension();
                $purchaseAttachment->move(public_path('uploads/purchase_attachment/'), $purchaseAttachmentName);
                $addPurchase->attachment = $purchaseAttachmentName;
            }
            $addPurchase->save();

            // add purchase or purchase order product
            if ($request->purchase_status == 1) {

                $this->purchaseUtil->addPurchaseProduct($request, $isEditProductPrice, $addPurchase->id);
            } else {

                $this->purchaseUtil->addPurchaseOrderProduct($request, $isEditProductPrice, $addPurchase->id);
            }

            // Add Purchase A/C Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 3,
                date: $request->date,
                account_id: $request->purchase_account_id,
                trans_id: $addPurchase->id,
                amount: $request->total_purchase_amount,
                balance_type: 'debit'
            );

            // Add supplier ledger For Purchase
            $this->supplierUtil->addSupplierLedger(
                voucher_type_id: 1,
                supplier_id: $request->supplier_id,
                branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $addPurchase->id,
                amount: $request->total_purchase_amount,
            );

            if ($request->paying_amount > 0) {

                // Add purchase payment
                $addPurchasePaymentGetId = $this->purchaseUtil->addPurchasePaymentGetId(
                    invoicePrefix: $paymentInvoicePrefix,
                    request: $request,
                    payingAmount: $request->paying_amount,
                    invoiceId: str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT),
                    purchase: $addPurchase,
                    supplier_payment_id: NULL
                );

                // Add Bank/Cash-In-Hand A/C Ledger
                $this->accountUtil->addAccountLedger(
                    voucher_type_id: 11,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $addPurchasePaymentGetId,
                    amount: $request->paying_amount,
                    balance_type: 'debit'
                );

                // Add supplier ledger for payment
                $this->supplierUtil->addSupplierLedger(
                    voucher_type_id: 3,
                    supplier_id: $request->supplier_id,
                    branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $addPurchasePaymentGetId,
                    amount: $request->paying_amount,
                );
            }

            // update main product and variant price
            $loop = 0;
            foreach ($product_ids as $productId) {

                $variant_id = $variant_ids[$loop] != 'noid' ? $variant_ids[$loop] : NULL;
                $__xMargin = isset($request->profits) ? $profits[$loop] : 0;
                $__sale_price = isset($request->selling_prices) ? $selling_prices[$loop] : 0;

                $this->purchaseUtil->updateProductAndVariantPrice(
                    $productId,
                    $variant_id,
                    $unit_costs_with_discount[$loop],
                    $net_unit_costs[$loop],
                    $__xMargin,
                    $__sale_price,
                    $isEditProductPrice,
                    $addPurchase->is_last_created
                );

                $loop++;
            }

            if ($request->purchase_status == 1) {

                $__index = 0;
                foreach ($product_ids as $productId) {

                    $variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
                    $this->productStockUtil->adjustMainProductAndVariantStock($productId, $variant_id);

                    if (isset($request->warehouse_count)) {

                        $this->productStockUtil->addWarehouseProduct($productId, $variant_id, $request->warehouse_id);
                        $this->productStockUtil->adjustWarehouseStock($productId, $variant_id, $request->warehouse_id);
                    } else {

                        $this->productStockUtil->addBranchProduct($productId, $variant_id, auth()->user()->branch_id);
                        $this->productStockUtil->adjustBranchStock($productId, $variant_id, auth()->user()->branch_id);
                    }
                    $__index++;
                }
            }

            // Add user Log
            $this->userActivityLogUtil->addLog(
                action: 1,
                subject_type: $request->purchase_status == 3 ? 5 : 4,
                data_obj: $addPurchase
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        // $this->supplierUtil->adjustSupplierForPurchasePaymentDue($request->supplier_id);
        if ($request->action == 2) {


                return response()->json(['successMsg' => __('Successfully purchase is created.')]);


        } else {

            if ($request->purchase_status == 3) {

                $purchase = Purchase::with([
                    'warehouse:id,warehouse_name,warehouse_code',
                    'branch',
                    'supplier',
                    'admin:id,prefix,name,last_name',
                    'purchase_order_products',
                    'purchase_products.product',
                    'purchase_products.product.warranty',
                    'purchase_products.variant',
                    'purchase_payments',
                ])->where('id', $addPurchase->id)->first();

                return view('purchases.save_and_print_template.print_order', compact('purchase'));
            } else {

                $purchase = Purchase::with([
                    'warehouse:id,warehouse_name,warehouse_code',
                    'branch',
                    'supplier',
                    'admin:id,prefix,name,last_name',
                    'purchase_products',
                    'purchase_products.product',
                    'purchase_products.product.warranty',
                    'purchase_products.variant',
                    'purchase_payments',
                ])->where('id', $addPurchase->id)->first();

                return view('purchases.save_and_print_template.print_purchase', compact('purchase'));
            }
        }


    }

    // Purchase edit view
    public function edit($purchaseId, $editType)
    {
        $purchaseId = $purchaseId;
        $editType = $editType;

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $purchase = DB::table('purchases')->where('id', $purchaseId)->select('id', 'warehouse_id', 'date', 'delivery_date', 'purchase_status')->first();

        $purchaseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 3)
            ->get(['accounts.id', 'accounts.name']);

        return view('purchases.edit', compact('purchaseId', 'warehouses', 'purchase', 'editType', 'purchaseAccounts'));
    }

    // update purchase method
    public function update(Request $request, $editType)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'purchase_account_id' => 'required',
        ], [
            'purchase_account_id.required' => 'Purchase A/C is required.',
        ]);

        try {

            DB::beginTransaction();

            $prefixSettings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
            $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_invoice'];
            $isEditProductPrice = json_decode($prefixSettings->purchase, true)['is_edit_pro_price'];

            if (isset($request->warehouse_count)) {

                $this->validate($request, ['warehouse_id' => 'required']);
            }

            if (!isset($request->product_ids)) {

                return response()->json(['errorMsg' => __('Product table is empty.')]);
            }

            $product_ids = $request->product_ids;
            $variant_ids = $request->variant_ids;
            $quantities = $request->quantities;
            $unit_costs_with_discount = $request->unit_costs_with_discount;
            $net_unit_costs = $request->net_unit_costs;
            $profits = $request->profits;
            $selling_prices = $request->selling_prices;

            // get updatable purchase row
            $updatePurchase = purchase::with(['purchase_products', 'purchase_order_products', 'ledger'])
                ->where('id', $request->id)->first();
            $storedWarehouseId = $updatePurchase->warehouse_id;
            $storePurchaseProducts = $updatePurchase->purchase_products;

            // update product and variant quantity for adjustment
            foreach ($updatePurchase->purchase_products as $purchase_product) {

                $SupplierProduct = SupplierProduct::where('supplier_id', $updatePurchase->supplier_id)
                    ->where('product_id', $purchase_product->product_id)
                    ->where('product_variant_id', $purchase_product->product_variant_id)
                    ->first();

                if ($SupplierProduct) {

                    $SupplierProduct->label_qty -= (float)$purchase_product->quantity;
                    $SupplierProduct->save();
                }
            }

            $purchaseOrOrderProducts = $editType == 'purchased' ? $updatePurchase->purchase_products : $updatePurchase->purchase_order_products;

            foreach ($purchaseOrOrderProducts as $purchaseOrOrderProduct) {

                $purchaseOrOrderProduct->delete_in_update = 1;
                $purchaseOrOrderProduct->save();
            }

            // update supplier product
            $i = 0;
            foreach ($product_ids as $product_id) {

                $variant_id = $variant_ids[$i] != 'noid' ? $variant_ids[$i] : NULL;

                $SupplierProduct = SupplierProduct::where('supplier_id', $updatePurchase->supplier_id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->first();

                if (!$SupplierProduct) {

                    $addSupplierProduct = new SupplierProduct();
                    $addSupplierProduct->supplier_id = $updatePurchase->supplier_id;
                    $addSupplierProduct->product_id = $product_id;
                    $addSupplierProduct->product_variant_id = $variant_id;
                    $addSupplierProduct->label_qty = $quantities[$i];
                    $addSupplierProduct->save();
                } else {

                    $SupplierProduct->label_qty = $SupplierProduct->label_qty + $quantities[$i];
                    $SupplierProduct->save();
                }
                $i++;
            }

            $updatePurchase->warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : NULL;

            $__invoicePrefix = '';
            if ($request->purchase_status == 1) {

                $__invoicePrefix = $invoicePrefix != null ? $invoicePrefix : '';
            } elseif ($request->purchase_status == 3) {

                $__invoicePrefix = 'PO';
            }

            // update purchase total information
            $updatePurchase->invoice_id = $request->invoice_id ? $request->invoice_id : $__invoicePrefix . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchases'), 5, "0", STR_PAD_LEFT);

            $updatePurchase->pay_term = $request->pay_term;
            $updatePurchase->pay_term_number = $request->pay_term_number;
            $updatePurchase->purchase_account_id = $request->purchase_account_id;
            // $updatePurchase->admin_id = auth()->user()->id;
            $updatePurchase->total_item = $request->total_item;
            $updatePurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
            $updatePurchase->order_discount_type = $request->order_discount_type;
            $updatePurchase->order_discount_amount = $request->order_discount_amount;
            $updatePurchase->purchase_tax_percent = $request->purchase_tax ? $request->purchase_tax : 0.00;
            $updatePurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
            $updatePurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
            $updatePurchase->net_total_amount = $request->net_total_amount;
            $updatePurchase->total_purchase_amount = $request->total_purchase_amount;
            $updatePurchase->shipment_details = $request->shipment_details;
            $updatePurchase->purchase_note = $request->purchase_note;
            $updatePurchase->purchase_status = $request->purchase_status;
            $updatePurchase->date = $request->date;
            $updatePurchase->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));

            if ($request->hasFile('attachment')) {

                if ($updatePurchase->attachment != null) {

                    if (file_exists(public_path('uploads/purchase_attachment/' . $updatePurchase->attachment))) {

                        unlink(public_path('uploads/purchase_attachment/' . $updatePurchase->attachment));
                    }
                }

                $purchaseAttachment = $request->file('attachment');
                $purchaseAttachmentName = uniqid() . '-' . '.' . $purchaseAttachment->getClientOriginalExtension();
                $purchaseAttachment->move(public_path('uploads/purchase_attachment/'), $purchaseAttachmentName);
                $updatePurchase->attachment = $purchaseAttachmentName;
            }
            $updatePurchase->save();

            // update product and variant Price & quantity
            if ($editType == 'purchased') {

                $loop = 0;
                foreach ($product_ids as $productId) {

                    $variant_id = $variant_ids[$loop] != 'noid' ? $variant_ids[$loop] : NULL;

                    $__xMargin = isset($request->profits) ? $profits[$loop] : 0;
                    $__sale_price = isset($request->selling_prices) ? $selling_prices[$loop] : 0;

                    $this->purchaseUtil->updateProductAndVariantPrice(
                        $productId,
                        $variant_id,
                        $unit_costs_with_discount[$loop],
                        $net_unit_costs[$loop],
                        $__xMargin,
                        $__sale_price,
                        $isEditProductPrice,
                        $updatePurchase->is_last_created
                    );

                    $loop++;
                }
            }

            if ($editType == 'purchased') {

                $this->purchaseUtil->updatePurchaseProduct($request, $isEditProductPrice, $updatePurchase->id);
            } else {

                $this->purchaseUtil->updatePurchaseOrderProduct($request, $isEditProductPrice, $updatePurchase->id);
            }

            // deleted not getting previous product
            $deletedUnusedPurchaseOrPoProducts = '';
            if ($editType == 'ordered') {

                $deletedUnusedPurchaseOrPoProducts = PurchaseOrderProduct::where('purchase_id', $updatePurchase->id)
                    ->where('delete_in_update', 1)
                    ->get();
            } else {

                $deletedUnusedPurchaseOrPoProducts = PurchaseProduct::where('purchase_id', $updatePurchase->id)
                    ->where('delete_in_update', 1)
                    ->get();
            }

            if (count($deletedUnusedPurchaseOrPoProducts) > 0) {

                foreach ($deletedUnusedPurchaseOrPoProducts as $deletedPurchaseProduct) {

                    $storedProductId = $deletedPurchaseProduct->product_id;
                    $storedVariantId = $deletedPurchaseProduct->product_variant_id;
                    $deletedPurchaseProduct->delete();
                    // Adjust deleted product stock
                    $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);

                    if (isset($request->warehouse_count)) {

                        $this->productStockUtil->adjustWarehouseStock($storedProductId, $storedVariantId, $request->warehouse_id);
                    } else {

                        $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, auth()->user()->branch_id);
                    }
                }
            }

            if ($editType == 'purchased') {

                $purchase_products = DB::table('purchase_products')->where('purchase_id', $updatePurchase->id)->get();
                foreach ($purchase_products as $purchase_product) {

                    $this->productStockUtil->adjustMainProductAndVariantStock($purchase_product->product_id, $purchase_product->product_variant_id);

                    if (isset($request->warehouse_count)) {

                        $this->productStockUtil->addWarehouseProduct($purchase_product->product_id, $purchase_product->product_variant_id, $request->warehouse_id);
                        $this->productStockUtil->adjustWarehouseStock($purchase_product->product_id, $purchase_product->product_variant_id, $request->warehouse_id);
                    } else {

                        $this->productStockUtil->addBranchProduct($purchase_product->product_id, $purchase_product->product_variant_id, auth()->user()->branch_id);
                        $this->productStockUtil->adjustBranchStock($purchase_product->product_id, $purchase_product->product_variant_id, auth()->user()->branch_id);
                    }
                }

                if (isset($request->warehouse_count) && $request->warehouse_id != $storedWarehouseId) {

                    foreach ($storePurchaseProducts as $PurchaseProduct) {

                        $this->productStockUtil->adjustWarehouseStock($PurchaseProduct->product_id, $PurchaseProduct->product_variant_id, $storedWarehouseId);
                    }
                }
            }

            // Update Purchase A/C Ledger
            $this->accountUtil->updateAccountLedger(
                voucher_type_id: 3,
                date: $request->date,
                account_id: $request->purchase_account_id,
                trans_id: $updatePurchase->id,
                amount: $request->total_purchase_amount,
                balance_type: 'debit'
            );

            // Update supplier ledger
            $this->supplierUtil->updateSupplierLedger(
                voucher_type_id: 1,
                supplier_id: $updatePurchase->supplier_id,
                previous_branch_id: auth()->user()->branch_id,
                new_branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $updatePurchase->id,
                amount: $request->total_purchase_amount
            );

            if ($editType == 'ordered') {

                $this->purchaseUtil->updatePoInvoiceQtyAndStatusPortion($updatePurchase);
            }

            $adjustedPurchase = $this->purchaseUtil->adjustPurchaseInvoiceAmounts($updatePurchase);

            // Add user Log
            $this->userActivityLogUtil->addLog(
                action: 2,
                subject_type: $request->purchase_status == 3 ? 5 : 4,
                data_obj: $adjustedPurchase
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($editType == 'ordered') {

                session()->flash('successMsg', [__('Successfully purchase is updated'), 'uncompleted_orders']);




        } else {
            session()->flash('successMsg', [__('Successfully purchase is updated'), 'uncompleted_orders']);


        }


            return response()->json(__('Successfully purchase is updated'));


    }

    // Get editable purchase
    public function editablePurchase($purchaseId, $editType)
    {
        if ($editType == 'purchased') {

            $purchase = Purchase::with(['warehouse', 'supplier', 'purchase_products', 'purchase_products.product', 'purchase_products.variant'])->where('id', $purchaseId)->first();
            return response()->json($purchase);
        } else {

            $purchase = Purchase::with(['warehouse', 'supplier', 'purchase_order_products', 'purchase_order_products.product', 'purchase_order_products.variant'])->where('id', $purchaseId)->first();
            return response()->json($purchase);
        }
    }

    // Get all supplier requested by ajax
    public function getAllSupplier()
    {
        $suppliers = Supplier::select('id',  'name',  'pay_term', 'pay_term_number', 'phone')->get();
        return response()->json($suppliers);
    }

    // Get all warehouse requested by ajax
    public function getAllUnit()
    {
        return Unit::select('id', 'name')->get();
    }

    // Get all warehouse requested by ajax
    public function getAllTax()
    {
        return DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();
    }

    // Search product by code
    public function searchProduct($product_code)
    {
        $__product_code = str_replace('~', '/', $product_code);

        $product = Product::with(['product_variants', 'tax', 'unit'])
            ->where('type', 1)
            ->where('product_code', $__product_code)
            ->where('status', 1)->first();

        if ($product) {

            $productBranch = DB::table('product_branches')->where('branch_id', auth()->user()->branch_id)->where('product_id', $product->id)->first();

            if (!$productBranch) {

                return response()->json(['errorMsg' => __('Product is not available in the Business Location')]);
            }

            return response()->json(['product' => $product]);
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $__product_code)
                ->first();

            if ($variant_product) {

                $productBranch = DB::table('product_branches')->where('branch_id', auth()->user()->branch_id)
                    ->where('product_id', $variant_product->product_id)->first();

                if (!$productBranch) {

                    return response()->json(['errorMsg' => __('Product is not available in the Business Location')]);
                }

                return response()->json(['variant_product' => $variant_product]);
            }
        }

        return $this->nameSearchUtil->nameSearching($__product_code);
    }

    // delete purchase method
    public function delete(Request $request, $purchaseId)
    {
        // get deleting purchase row
        $deletePurchase = purchase::with([
            'supplier',
            'purchase_products',
            'purchase_products.product',
            'purchase_products.variant',
            'purchase_products.purchaseSaleChains'
        ])->where('id', $purchaseId)->first();

        $supplier = DB::table('suppliers')->where('id', $deletePurchase->supplier_id)->first();
        //purchase payments
        $storedWarehouseId = $deletePurchase->warehouse_id;
        $storedPurchaseReturnAccountId = $deletePurchase->purchase_return ? $deletePurchase->purchase_return->purchase_return_account_id : NULL;
        $storedBranchId = $deletePurchase->branch_id;
        $storedPayments = $deletePurchase->purchase_payments;
        $storedPurchaseAccountId = $deletePurchase->purchase_account_id;
        $storePurchaseProducts = $deletePurchase->purchase_products;

        foreach ($deletePurchase->purchase_products as $purchase_product) {

            if (count($purchase_product->purchaseSaleChains) > 0) {

                $variant = $purchase_product->variant ? ' - ' . $purchase_product->variant->name : '';
                $product = $purchase_product->product->name . $variant;
                return response()->json("Can not delete is purchase. Mismatch between sold and purchase stock account method. Product: ${product}");
            }
        }

        foreach ($deletePurchase->purchase_products as $purchase_product) {

            $SupplierProduct = SupplierProduct::where('supplier_id', $deletePurchase->supplier_id)
                ->where('product_id', $purchase_product->product_id)
                ->where('product_variant_id', $purchase_product->product_variant_id)
                ->first();

            if ($SupplierProduct) {

                $SupplierProduct->label_qty -= $purchase_product->quantity;
                $SupplierProduct->save();
            }
        }

        // Add user Log
        $this->userActivityLogUtil->addLog(
            action: 3,
            subject_type: $deletePurchase->purchase_status == 3 ? 5 : 4,
            data_obj: $deletePurchase
        );

        $deletePurchase->delete();

        if ($storedPurchaseAccountId) {

            $this->accountUtil->adjustAccountBalance('debit', $storedPurchaseAccountId);
        }

        if ($storedPurchaseReturnAccountId) {

            $this->accountUtil->adjustAccountBalance('credit', $storedPurchaseReturnAccountId);
        }

        foreach ($storePurchaseProducts as $purchase_product) {

            $variant_id = $purchase_product->product_variant_id ? $purchase_product->product_variant_id : NULL;

            $this->productStockUtil->adjustMainProductAndVariantStock($purchase_product->product_id, $variant_id);

            if ($storedWarehouseId) {

                $this->productStockUtil->adjustWarehouseStock($purchase_product->product_id, $variant_id, $storedWarehouseId);
            } else {

                $this->productStockUtil->adjustBranchStock($purchase_product->product_id, $variant_id, $storedBranchId);
            }
        }

        if (count($storedPayments) > 0) {

            foreach ($storedPayments as $payment) {

                if ($payment->account_id) {

                    $this->accountUtil->adjustAccountBalance('debit', $payment->account_id);
                }
            }
        }

        $this->supplierUtil->adjustSupplierForPurchasePaymentDue($supplier->id);

        return response()->json(__('Successfully purchase is deleted'));
    }

    // Add product modal view with data
    public function addProductModalVeiw()
    {
        $units =  DB::table('units')->select('id', 'name', 'code_name')->get();

        $warranties = DB::table('warranties')->select('id', 'name', 'type')->get();

        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();

        $categories =  DB::table('categories')->where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();

        $brands = DB::table('brands')->get();
        return view('purchases.ajax_view.add_product_modal_view', compact('units', 'warranties', 'taxes', 'categories', 'brands'));
    }

    // Add product from purchase
    public function addProduct(Request $request)
    {
        return $this->util->addQuickProductFromPurchase($request);
    }

    // Get recent added product which has been added from purchase
    public function getRecentProduct($product_id)
    {
        $product = Product::with(['tax', 'unit'])->where('id', $product_id)->first();
        $units = DB::table('units')->select('id', 'name')->get();
        return view('purchases.ajax_view.recent_product_view', compact('product', 'units'));
    }

    // Get quick supplier modal
    public function addQuickSupplierModal()
    {
        $branches=Branch::all();
        return view('purchases.ajax_view.add_quick_supplier', compact('branches'));
    }

    // Change purchase status
    public function changeStatus(Request $request, $purchaseId)
    {
        $purchase = Purchase::where('id', $purchaseId)->first();

        $purchase->purchase_status = $request->purchase_status;

        $purchase->save();


        return response()->json(__('Successfully purchase status is changed.'));

    }

    public function paymentModal($purchaseId)
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

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $purchase = Purchase::with(['supplier', 'branch', 'warehouse'])->where('id', $purchaseId)->first();

        return view('purchases.ajax_view.purchase_payment_modal', compact('purchase', 'accounts', 'methods'));
    }

    public function paymentStore(Request $request, $purchaseId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();

        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];

        $purchase = Purchase::where('id', $purchaseId)->first();

        if ($request->paying_amount > 0) {
            // Add purchase payment
            $addPurchasePaymentGetId = $this->purchaseUtil->addPurchasePaymentGetId(
                invoicePrefix: $paymentInvoicePrefix,
                request: $request,
                payingAmount: $request->paying_amount,
                invoiceId: str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT),
                purchase: $purchase,
                supplier_payment_id: NULL
            );

            // Add Bank/Cash-In-Hand A/C Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 11,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $addPurchasePaymentGetId,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            // Add supplier ledger
            $this->supplierUtil->addSupplierLedger(
                voucher_type_id: 3,
                supplier_id: $purchase->supplier_id,
                branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $addPurchasePaymentGetId,
                amount: $request->paying_amount,
            );

            $purchasePayment = DB::table('purchase_payments')
                ->where('purchase_payments.id', $addPurchasePaymentGetId)
                ->leftJoin('suppliers', 'purchase_payments.supplier_id', 'suppliers.id')
                ->leftJoin('payment_methods', 'purchase_payments.payment_method_id', 'payment_methods.id')
                ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
                ->select(
                    'purchase_payments.invoice_id as voucher_no',
                    'purchase_payments.date',
                    'purchase_payments.paid_amount',
                    'suppliers.name as supplier',
                    'suppliers.phone',
                    'payment_methods.name as method',
                    'purchases.invoice_id as agp',
                )->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 28, data_obj: $purchasePayment);

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        }


            return response()->json(__('Payment added successfully.'));


    }

    public function paymentEdit($paymentId)
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

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $payment = PurchasePayment::with(['purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier'])
            ->where('id', $paymentId)->first();

        return view('purchases.ajax_view.purchase_payment_edit_modal', compact('payment', 'accounts', 'methods'));
    }

    public function paymentUpdate(Request $request, $paymentId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        if ($request->paying_amount > 0) {

            $updatePurchasePayment = PurchasePayment::with(
                'account',
                'purchase.purchase_return',
            )->where('id', $paymentId)->first();

            $purchase = Purchase::where('id', $updatePurchasePayment->purchase_id)->first();

            $this->purchaseUtil->updatePurchasePayment($request, $updatePurchasePayment);

            if ($updatePurchasePayment->supplier_payment_id == NULL) {

                // Update Bank/Cash-in-hand A/C Ledger
                $this->accountUtil->updateAccountLedger(
                    voucher_type_id: 11,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $updatePurchasePayment->id,
                    amount: $request->paying_amount,
                    balance_type: 'debit'
                );

                // Update supplier ledger
                $this->supplierUtil->updateSupplierLedger(
                    voucher_type_id: 3,
                    supplier_id: $purchase->supplier_id,
                    previous_branch_id: auth()->user()->branch_id,
                    new_branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $updatePurchasePayment->id,
                    amount: $request->paying_amount
                );
            }

            $purchasePayment = DB::table('purchase_payments')
                ->where('purchase_payments.id', $updatePurchasePayment->id)
                ->leftJoin('suppliers', 'purchase_payments.supplier_id', 'suppliers.id')
                ->leftJoin('payment_methods', 'purchase_payments.payment_method_id', 'payment_methods.id')
                ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
                ->select(
                    'purchase_payments.invoice_id as voucher_no',
                    'purchase_payments.date',
                    'purchase_payments.paid_amount',
                    'suppliers.name as supplier',
                    'suppliers.phone',
                    'payment_methods.name as method',
                    'purchases.invoice_id as agp',
                )->first();

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 28, data_obj: $purchasePayment);

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
        }

        return response()->json(__('Payment updated successfully.'));

    }

    public function returnPaymentModal($purchaseId)
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

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $purchase = Purchase::with(['supplier', 'branch', 'warehouse'])->where('id', $purchaseId)->first();

        return view('purchases.ajax_view.purchase_return_payment', compact('purchase', 'accounts', 'methods'));
    }

    public function change_status($id)
    {
        $statusChange = Purchase::where('id', $id)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Purchase is deactivated Successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();
            return response()->json(__('Purchase is activated Successfully'));

        }
    }

    public function returnPaymentStore(Request $request, $purchaseId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        $purchase = Purchase::with(['purchase_return'])->where('id', $purchaseId)->first();

        if ($request->paying_amount > 0) {

            $purchaseReturnPaymentGetId = $this->purchaseUtil->purchaseReturnPaymentGetId(
                request: $request,
                purchase: $purchase,
                supplier_payment_id: NULL
            );

            // Add Bank/Cash-In-Hand A/C Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 17,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $purchaseReturnPaymentGetId,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            // Add supplier ledger
            $this->supplierUtil->addSupplierLedger(
                voucher_type_id: 4,
                supplier_id: $purchase->supplier_id,
                branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $purchaseReturnPaymentGetId,
                amount: $request->paying_amount,
            );

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);

            // update purchase return
            if ($purchase->purchase_return) {

                $this->purchaseReturnUtil->adjustPurchaseReturnAmounts($purchase->purchase_return);
            }
        }

        return response()->json(__('Payment added successfully.'));
    }

    public function returnPaymentEdit($paymentId)
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

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        $payment = PurchasePayment::with(['purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier'])
            ->where('id', $paymentId)->first();
        return view('purchases.ajax_view.purchase_return_payment_edit', compact('payment', 'accounts', 'methods'));
    }

    public function returnPaymentUpdate(Request $request, $paymentId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        if ($request->paying_amount > 0) {

            $updatePurchasePayment = PurchasePayment::where('id', $paymentId)->first();

            $purchase = Purchase::with('purchase_return')
                ->where('id', $updatePurchasePayment->purchase_id)->first();

            $this->purchaseUtil->updatePurchaseReturnPayment($request, $updatePurchasePayment);

            if ($updatePurchasePayment->supplier_payment_id == NULL) {

                // Update Bank/Cash-in-hand A/C Ledger
                $this->accountUtil->updateAccountLedger(
                    voucher_type_id: 17,
                    date: $request->date,
                    account_id: $request->account_id,
                    trans_id: $updatePurchasePayment->id,
                    amount: $request->paying_amount,
                    balance_type: 'debit'
                );

                // Update supplier ledger
                $this->supplierUtil->updateSupplierLedger(
                    voucher_type_id: 4,
                    supplier_id: $purchase->supplier_id,
                    previous_branch_id: auth()->user()->branch_id,
                    new_branch_id: auth()->user()->branch_id,
                    date: $request->date,
                    trans_id: $updatePurchasePayment->id,
                    amount: $request->paying_amount
                );
            }

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);

            // update purchase return
            if ($purchase->purchase_return) {

                $this->purchaseReturnUtil->adjustPurchaseReturnAmounts($purchase->purchase_return);
            }
        }

        return response()->json(__('Payment updated successfully.'));
    }

    //Get purchase wise payment list
    public function paymentList($purchaseId)
    {
        $purchase = Purchase::with([
            'supplier',
            'purchase_payments',
            'purchase_payments.account',
            'purchase_payments.paymentMethod'
        ])->where('id', $purchaseId)->first();

        return view('purchases.ajax_view.view_payment_list', compact('purchase'));
    }

    public function paymentDetails($paymentId)
    {
        $payment = PurchasePayment::with(
            'paymentMethod',
            'purchase',
            'purchase.branch',
            'purchase.warehouse',
            'purchase.supplier'
        )->where('id', $paymentId)->first();

        return view('purchases.ajax_view.payment_details', compact('payment'));
    }

    // Delete purchase payment
    public function paymentDelete(Request $request, $paymentId)
    {
        $deletePurchasePayment = PurchasePayment::with('account', 'purchase', 'purchase.purchase_return')
            ->where('id', $paymentId)
            ->first();

        if (!is_null($deletePurchasePayment)) {

            $storedAccountId = $deletePurchasePayment->account_id;
            if ($deletePurchasePayment->attachment != null) {

                if (file_exists(public_path('uploads/payment_attachment/' . $deletePurchasePayment->attachment))) {

                    unlink(public_path('uploads/payment_attachment/' . $deletePurchasePayment->attachment));
                }
            }

            //Update Supplier due
            if ($deletePurchasePayment->payment_type == 1) {

                $storedSupplierId = $deletePurchasePayment->purchase->supplier_id;
                $storedPurchaseId = $deletePurchasePayment->purchase_id;

                $purchasePayment = DB::table('purchase_payments')
                    ->where('purchase_payments.id', $deletePurchasePayment->id)
                    ->leftJoin('suppliers', 'purchase_payments.supplier_id', 'suppliers.id')
                    ->leftJoin('payment_methods', 'purchase_payments.payment_method_id', 'payment_methods.id')
                    ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
                    ->select(
                        'purchase_payments.invoice_id as voucher_no',
                        'purchase_payments.date',
                        'purchase_payments.paid_amount',
                        'suppliers.name as supplier',
                        'suppliers.phone',
                        'payment_methods.name as method',
                        'purchases.invoice_id as agp',
                    )->first();

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 28, data_obj: $purchasePayment);

                $deletePurchasePayment->delete();

                if ($storedPurchaseId) {

                    $purchase = Purchase::where('id', $storedPurchaseId)->first();
                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                }

                $this->supplierUtil->adjustSupplierForPurchasePaymentDue($storedSupplierId);
            } else {
                if ($deletePurchasePayment->purchase) {

                    $storedPurchase = $deletePurchasePayment->purchase;
                    $storedPurchaseReturn = $deletePurchasePayment->purchase->purchase_return;
                    $deletePurchasePayment->delete();

                    // update purchase return
                    if ($storedPurchaseReturn) {

                        $this->purchaseReturnUtil->adjustPurchaseReturnAmounts($storedPurchaseReturn);
                    }

                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($storedPurchase);
                    $this->supplierUtil->adjustSupplierForPurchasePaymentDue($storedPurchase->supplier_id);
                } else {

                    $purchaseReturn = PurchaseReturn::where('id', $deletePurchasePayment->supplier_return->id)->first();
                    $purchaseReturn->total_return_due_received -= $deletePurchasePayment->paid_amount;
                    $purchaseReturn->total_return_due += $deletePurchasePayment->paid_amount;
                    $purchaseReturn->save();
                    $deletePurchasePayment->delete();
                    $this->supplierUtil->adjustSupplierForPurchasePaymentDue($purchaseReturn->supplier_id);
                }
            }

            if ($storedAccountId) {

                $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
            }
        }

        return response()->json(__('Payment deleted successfully.'));
    }

    //Show Change status modal
    public function settings()
    {
        return view('purchases.settings.index');
    }

    //Show Change status modal
    public function settingsStore(Request $request)
    {
        $updatePurchaseSettings = General_setting::first();
        $purchaseSettings = [
            'is_edit_pro_price' => isset($request->is_edit_pro_price) ? 1 : 0,
            'is_enable_status' => isset($request->is_enable_status) ? 1 : 0,
            'is_enable_lot_no' => isset($request->is_enable_lot_no) ? 1 : 0,
        ];

        $updatePurchaseSettings->purchase = json_encode($purchaseSettings);
        $updatePurchaseSettings->save();

        return response()->json(__('Purchase settings updated successfully.'));

    }
}
