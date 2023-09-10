<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Purchase;
use App\Utils\AccountUtil;
use App\Utils\PurchaseUtil;
use App\Utils\SupplierUtil;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\SupplierLedger;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use App\Utils\ProductStockUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderProduct;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Models\PurchaseOrderProductReceive;

class PurchaseOrderReceiveController extends Controller
{
    protected $accountUtil;
    protected $invoiceVoucherRefIdUtil;
    protected $supplierUtil;
    protected $productStockUtil;
    protected $purchaseUtil;
    protected $userActivityLogUtil;

    public function __construct(
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        SupplierUtil $supplierUtil,
        ProductStockUtil $productStockUtil,
        PurchaseUtil $purchaseUtil,
        UserActivityLogUtil $userActivityLogUtil,
    ) {
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->supplierUtil = $supplierUtil;
        $this->productStockUtil = $productStockUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function processReceive($purchaseId)
    {
        $purchase = Purchase::with([
            'supplier:id,name,phone',
            'purchase_order_products',
            'purchase_order_products.receives',
            'purchase_order_products.product',
            'purchase_order_products.variant',
        ])->where('id', $purchaseId)->first();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $warehouses = DB::table('warehouses')->where('branch_id', auth()->user()->branch_id)->get();

        return view('purchases.order_receive.process_to_receive', compact('purchase', 'warehouses', 'accounts', 'methods'));
    }

    public function processReceiveStore(Request $request, $purchaseId)
    {
        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly

            $prefixSettings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
            $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_invoice'];
            $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];
            $isEditProductPrice = json_decode($prefixSettings->purchase, true)['is_edit_pro_price'];

            $purchase = Purchase::where('id', $purchaseId)->first();
            $purchase->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . $this->invoiceVoucherRefIdUtil->getLastId('purchases');
            $purchase->po_pending_qty = $request->total_pending;
            $purchase->po_received_qty = $request->total_received;
            $purchase->is_purchased = $request->total_received > 0 ? 1 : $purchase->is_purchased;
            $purchase->date = $request->date;
            $purchase->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
            $purchase->save();

            // Update Purchase order Product
            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;
                $purchaseOrderProduct = PurchaseOrderProduct::where('purchase_id', $purchase->id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)->first();

                if ($purchaseOrderProduct) {

                    $purchaseOrderProduct->pending_quantity = (float)$request->pending_quantities[$index];
                    $purchaseOrderProduct->received_quantity = (float)$request->received_quantities[$index];
                    $purchaseOrderProduct->save();
                }

                $index++;
            }

            if (isset($request->or_receive_rows)) {

                foreach ($request->or_receive_rows as $id => $value) {

                    $valueIndex = 0;
                    foreach ($value['purchase_challan'] as $challan) {

                        $updateReceiveRow = PurchaseOrderProductReceive::where('id', $value['receive_id'][$valueIndex])->first();

                        if ($updateReceiveRow) {

                            $updateReceiveRow->purchase_challan = $challan;
                            $updateReceiveRow->lot_number = $value['lot_number'][$valueIndex];
                            $updateReceiveRow->received_date = $value['received_date'][$valueIndex];
                            $updateReceiveRow->report_date = date('Y-m-d H:i:s', strtotime($value['received_date'][$valueIndex] . date(' H:i:s')));
                            $updateReceiveRow->qty_received = $value['qty_received'][$valueIndex];
                            $updateReceiveRow->save();
                        } else {

                            if ($value['qty_received'][$valueIndex] && $value['qty_received'][$valueIndex] > 0) {

                                $addReceiveRow = new PurchaseOrderProductReceive();
                                $addReceiveRow->order_product_id = $id;
                                $addReceiveRow->purchase_challan = $challan;
                                $addReceiveRow->lot_number = $value['lot_number'][$valueIndex];
                                $addReceiveRow->received_date = $value['received_date'][$valueIndex];
                                $addReceiveRow->report_date = date('Y-m-d H:i:s', strtotime($value['received_date'][$valueIndex] . date(' H:i:s')));
                                $addReceiveRow->qty_received = $value['qty_received'][$valueIndex];
                                $addReceiveRow->save();
                            }
                        }
                        $valueIndex++;
                    }
                }
            }

            // Add received product to purchase products table
            $purchase_order_products = DB::table('purchase_order_products')
                ->where('purchase_id', $purchase->id)->get();

            foreach ($purchase_order_products as $purchase_order_product) {

                $purchaseProduct = PurchaseProduct::where('purchase_id', $purchase->id)
                    ->where('product_order_product_id', $purchase_order_product->id)
                    ->first();

                if ($purchaseProduct) {

                    $purchaseProduct->quantity = $purchase_order_product->received_quantity;
                    $purchaseProduct->unit = $purchase_order_product->unit;
                    $purchaseProduct->unit_cost = $purchase_order_product->unit_cost;
                    $purchaseProduct->unit_discount = $purchase_order_product->unit_discount;
                    $purchaseProduct->unit_cost_with_discount = $purchase_order_product->unit_cost_with_discount;
                    $purchaseProduct->unit_tax_percent = $purchase_order_product->unit_tax_percent;
                    $purchaseProduct->unit_tax = $purchase_order_product->unit_tax;
                    $purchaseProduct->net_unit_cost = $purchase_order_product->net_unit_cost;
                    $purchaseProduct->subtotal = $purchase_order_product->received_quantity * $purchase_order_product->unit_cost_with_discount;
                    $purchaseProduct->line_total = $purchase_order_product->received_quantity * $purchase_order_product->net_unit_cost;
                    $purchaseProduct->profit_margin = $purchase_order_product->profit_margin;
                    $purchaseProduct->selling_price = $purchase_order_product->selling_price;
                    $purchaseProduct->lot_no = $purchase_order_product->lot_no;
                    $purchaseProduct->save();

                    // update product and variant Price & quantity
                    if ($purchase->is_last_created == 1) {

                        $this->purchaseUtil->updateProductAndVariantPrice($purchase_order_product->product_id, $purchase_order_product->product_variant_id, $purchase_order_product->unit_cost_with_discount, $purchase_order_product->net_unit_cost, $purchase_order_product->profit_margin, $purchase_order_product->selling_price, $isEditProductPrice, $purchase->is_last_created);
                    }

                    $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                } else {

                    if ($purchase_order_product->received_quantity != 0) {

                        $addPurchaseProduct = new PurchaseProduct();
                        $addPurchaseProduct->purchase_id = $purchase->id;
                        $addPurchaseProduct->product_order_product_id = $purchase_order_product->id;
                        $addPurchaseProduct->product_id = $purchase_order_product->product_id;
                        $addPurchaseProduct->product_variant_id = $purchase_order_product->product_variant_id;
                        $addPurchaseProduct->quantity = $purchase_order_product->received_quantity;
                        $addPurchaseProduct->left_qty =  $purchase_order_product->received_quantity;
                        $addPurchaseProduct->unit = $purchase_order_product->unit;
                        $addPurchaseProduct->unit_cost = $purchase_order_product->unit_cost;
                        $addPurchaseProduct->unit_discount = $purchase_order_product->unit_discount;
                        $addPurchaseProduct->unit_cost_with_discount = $purchase_order_product->unit_cost_with_discount;
                        $addPurchaseProduct->unit_tax_percent = $purchase_order_product->unit_tax_percent;
                        $addPurchaseProduct->unit_tax = $purchase_order_product->unit_tax;
                        $addPurchaseProduct->net_unit_cost = $purchase_order_product->net_unit_cost;
                        $addPurchaseProduct->subtotal = $purchase_order_product->received_quantity * $purchase_order_product->unit_cost_with_discount;
                        $addPurchaseProduct->line_total = $purchase_order_product->received_quantity * $purchase_order_product->unit_cost;
                        $addPurchaseProduct->profit_margin = $purchase_order_product->profit_margin;
                        $addPurchaseProduct->selling_price = $purchase_order_product->selling_price;
                        $addPurchaseProduct->lot_no = $purchase_order_product->lot_no;
                        $addPurchaseProduct->description = $purchase_order_product->description;
                        $addPurchaseProduct->save();

                        $this->purchaseUtil->updateProductAndVariantPrice($purchase_order_product->product_id, $purchase_order_product->product_variant_id, $purchase_order_product->unit_cost_with_discount, $purchase_order_product->net_unit_cost, $purchase_order_product->profit_margin, $purchase_order_product->selling_price, $isEditProductPrice, $purchase->is_last_created);
                    }
                }
            }

            // Add purchase payment
            if ($request->paying_amount > 0) {

                // Add purchase payment
                $addPurchasePaymentGetId = $this->purchaseUtil->addPurchasePaymentGetId(
                    invoicePrefix: $paymentInvoicePrefix,
                    request: $request,
                    payingAmount: $request->paying_amount,
                    invoiceId: str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT),
                    purchase: $purchase,
                    supplier_payment_id: NULL,
                    fixed_payment_date: $request->fixed_payment_date
                );

                // Add Bank/Cash-In-Hand A/C Ledger
                $this->accountUtil->addAccountLedger(
                    voucher_type_id: 11,
                    date: $request->fixed_payment_date,
                    account_id: $request->account_id,
                    trans_id: $addPurchasePaymentGetId,
                    amount: $request->paying_amount,
                    balance_type: 'debit'
                );

                // Add supplier ledger for payment
                $this->supplierUtil->addSupplierLedger(
                    voucher_type_id: 3,
                    supplier_id: $purchase->supplier_id,
                    branch_id: auth()->user()->branch_id,
                    date: $request->fixed_payment_date,
                    trans_id: $addPurchasePaymentGetId,
                    amount: $request->paying_amount,
                );

                $orderPayment = DB::table('purchase_payments')
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

                $this->userActivityLogUtil->addLog(action: 1, subject_type: 28, data_obj: $orderPayment);
            }

            $purchase_products = DB::table('purchase_products')->where('purchase_id', $purchase->id)->get();

            if (count($purchase_products) > 0) {

                foreach ($purchase_products as $purchase_product) {

                    $this->productStockUtil->adjustMainProductAndVariantStock($purchase_product->product_id, $purchase_product->product_variant_id);

                    if ($purchase->warehouse_id) {

                        $this->productStockUtil->addWarehouseProduct($purchase_product->product_id, $purchase_product->product_variant_id, $request->warehouse_id);
                        $this->productStockUtil->adjustWarehouseStock($purchase_product->product_id, $purchase_product->product_variant_id, $request->warehouse_id);
                    } else {

                        $this->productStockUtil->addBranchProduct($purchase_product->product_id, $purchase_product->product_variant_id, auth()->user()->branch_id);
                        $this->productStockUtil->adjustBranchStock($purchase_product->product_id, $purchase_product->product_variant_id, auth()->user()->branch_id);
                    }
                }
            }

            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
            $this->supplierUtil->adjustSupplierForPurchasePaymentDue($purchase->supplier_id);

            $this->purchaseUtil->updatePoInvoiceQtyAndStatusPortion($purchase);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', [__('Successfully order receiving is modified.'), 'uncompleted_orders']);

            return response()->json(__('Successfully order receiving is modified.'));


    }
}
