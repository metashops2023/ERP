<?php

namespace App\Utils;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\CashFlow;
use App\Models\SalePayment;
use App\Utils\CustomerUtil;
use Illuminate\Support\Str;
use App\Models\CustomerLedger;
use App\Models\CustomerPayment;
use App\Models\PurchaseProduct;
use App\Utils\ProductStockUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseSaleProductChain;
use Yajra\DataTables\Facades\DataTables;

class SaleUtil
{
    protected $customerUtil;
    protected $productStockUtil;
    protected $accountUtil;
    protected $converter;
    protected $invoiceVoucherRefIdUtil;
    protected $purchaseUtil;
    protected $userActivityLogUtil;

    public function __construct(
        CustomerUtil $customerUtil,
        ProductStockUtil $productStockUtil,
        AccountUtil $accountUtil,
        Converter $converter,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        PurchaseUtil $purchaseUtil,
        UserActivityLogUtil $userActivityLogUtil
    ) {
        $this->customerUtil = $customerUtil;
        $this->productStockUtil = $productStockUtil;
        $this->accountUtil = $accountUtil;
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
    }

    public function __getSalePaymentForAddSaleStore($request, $addSale, $paymentInvoicePrefix)
    {
        if ($request->paying_amount > 0) {

            $changedAmount = $request->change_amount > 0 ? $request->change_amount : 0.00;
            $paidAmount = $request->paying_amount - $changedAmount;

            if ($request->previous_due > 0) {

                if ($paidAmount >= $request->total_invoice_payable) {

                    $addPaymentGetId = $this->addPaymentGetId(
                        invoicePrefix: $paymentInvoicePrefix,
                        request: $request,
                        payingAmount: $request->total_invoice_payable,
                        invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                        saleId: $addSale->id,
                        customerPaymentId: NULL
                    );

                    // Add bank/cash-in-hand A/C ledger
                    $this->accountUtil->addAccountLedger(
                        voucher_type_id: 10,
                        date: $request->date ?? date('Y-m-d'),
                        account_id: $request->account_id,
                        trans_id: $addPaymentGetId,
                        amount: $request->total_invoice_payable,
                        balance_type: 'debit'
                    );

                    if ($request->customer_id) {
                        // add customer ledger
                        $this->customerUtil->addCustomerLedger(
                            voucher_type_id: 3,
                            customer_id: $request->customer_id,
                            branch_id: auth()->user()->branch_id,
                            date: $request->date ?? date('Y-m-d'),
                            trans_id: $addPaymentGetId,
                            amount: $request->total_invoice_payable
                        );
                    }

                    $payingPreviousDue = $paidAmount - $request->total_invoice_payable;

                    $addSale->previous_due_paid = $payingPreviousDue;

                    if ($payingPreviousDue > 0) {

                        $dueAmounts = $payingPreviousDue;
                        $dueInvoices = Sale::where('customer_id', $request->customer_id)
                            ->where('branch_id', auth()->user()->branch_id)
                            ->where('due', '>', 0)
                            ->get();

                        if (count($dueInvoices) > 0) {
                            $index = 0;
                            foreach ($dueInvoices as $dueInvoice) {

                                if ($dueInvoice->due > $dueAmounts) {

                                    if ($dueAmounts > 0) {

                                        // add sale payment
                                        $addPaymentGetId = $this->addPaymentGetId(
                                            invoicePrefix: $paymentInvoicePrefix,
                                            request: $request,
                                            payingAmount: $dueAmounts,
                                            invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                                            saleId: $dueInvoice->id,
                                            customerPaymentId: NULL
                                        );

                                        // Add bank/cash-in-hand A/C ledger
                                        $this->accountUtil->addAccountLedger(
                                            voucher_type_id: 10,
                                            date: $request->date ?? date('Y-m-d'),
                                            account_id: $request->account_id,
                                            trans_id: $addPaymentGetId,
                                            amount: $dueAmounts,
                                            balance_type: 'debit'
                                        );

                                        if ($request->customer_id) {
                                            // add customer ledger
                                            $this->customerUtil->addCustomerLedger(
                                                voucher_type_id: 3,
                                                customer_id: $request->customer_id,
                                                branch_id: auth()->user()->branch_id,
                                                date: $request->date ?? date('Y-m-d'),
                                                trans_id: $addPaymentGetId,
                                                amount: $dueAmounts
                                            );
                                        }

                                        $dueAmounts -= $dueAmounts;

                                        $this->adjustSaleInvoiceAmounts($dueInvoice);
                                    }
                                } elseif ($dueInvoice->due == $dueAmounts) {

                                    if ($dueAmounts > 0) {

                                        $addPaymentGetId = $this->addPaymentGetId(
                                            invoicePrefix: $paymentInvoicePrefix,
                                            request: $request,
                                            payingAmount: $dueAmounts,
                                            invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                                            saleId: $dueInvoice->id,
                                            customerPaymentId: NULL
                                        );

                                        // Add bank/cash-in-hand A/C Ledger
                                        $this->accountUtil->addAccountLedger(
                                            voucher_type_id: 10,
                                            date: $request->date ?? date('Y-m-d'),
                                            account_id: $request->account_id,
                                            trans_id: $addPaymentGetId,
                                            amount: $dueAmounts,
                                            balance_type: 'debit'
                                        );

                                        if ($request->customer_id) {
                                            // Add customer Ledger
                                            $this->customerUtil->addCustomerLedger(
                                                voucher_type_id: 3,
                                                customer_id: $request->customer_id,
                                                branch_id: auth()->user()->branch_id,
                                                date: $request->date ?? date('Y-m-d'),
                                                trans_id: $addPaymentGetId,
                                                amount: $dueAmounts
                                            );
                                        }

                                        $dueAmounts -= $dueAmounts;

                                        $this->adjustSaleInvoiceAmounts($dueInvoice);
                                    }
                                } elseif ($dueInvoice->due < $dueAmounts) {
                                    if ($dueInvoice->due > 0) {
                                        // add payment
                                        $addPaymentGetId = $this->addPaymentGetId(
                                            invoicePrefix: $paymentInvoicePrefix,
                                            request: $request,
                                            payingAmount: $dueInvoice->due,
                                            invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                                            saleId: $dueInvoice->id,
                                            customerPaymentId: NULL
                                        );

                                        // Add bank/cash-in-hand A/C Ledger
                                        $this->accountUtil->addAccountLedger(
                                            voucher_type_id: 10,
                                            date: $request->date ?? date('Y-m-d'),
                                            account_id: $request->account_id,
                                            trans_id: $addPaymentGetId,
                                            amount: $dueInvoice->due,
                                            balance_type: 'debit'
                                        );

                                        if ($request->customer_id) {
                                            // add customer ledger
                                            $this->customerUtil->addCustomerLedger(
                                                voucher_type_id: 3,
                                                customer_id: $request->customer_id,
                                                branch_id: auth()->user()->branch_id,
                                                date: $request->date ?? date('Y-m-d'),
                                                trans_id: $addPaymentGetId,
                                                amount: $dueInvoice->due
                                            );
                                        }

                                        $dueAmounts = $dueAmounts - $dueInvoice->due;
                                        $this->adjustSaleInvoiceAmounts($dueInvoice);
                                    }
                                }
                                $index++;
                            }
                        }

                        //DB::table('test')->insert(['test_value' => $dueAmounts]);

                        if ($dueAmounts > 0) {

                            $__report_date = '';
                            if (isset($request->date)) {

                                $__report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                            } else {

                                $__report_date = date('Y-m-d H:i:s');
                            }

                            // Add Customer Payment Record
                            $voucher_no = str_pad($this->invoiceVoucherRefIdUtil->getLastId('customer_payments'), 5, "0", STR_PAD_LEFT);
                            $customerPayment = new CustomerPayment();

                            $customerPayment->voucher_no = 'CPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('customer_payments'), 5, "0", STR_PAD_LEFT);

                            $customerPayment->branch_id = auth()->user()->branch_id;
                            $customerPayment->customer_id = $addSale->customer_id;
                            $customerPayment->account_id = $request->account_id;
                            $customerPayment->paid_amount = $dueAmounts;
                            $customerPayment->payment_method_id = $request->payment_method_id;
                            $customerPayment->date = $request->date ?? date('d-m-Y');
                            $customerPayment->time = date('h:i:s a');
                            $customerPayment->report_date = $__report_date;
                            $customerPayment->month = date('F');
                            $customerPayment->year = date('Y');
                            $customerPayment->note = $request->note;
                            $customerPayment->save();

                            // Add bank/cash-in-hand A/C Ledger
                            $this->accountUtil->addAccountLedger(
                                voucher_type_id: 18,
                                date: $request->date ?? date('Y-m-d'),
                                account_id: $request->account_id,
                                trans_id: $customerPayment->id,
                                amount: $dueAmounts,
                                balance_type: 'debit'
                            );

                            // add customer ledger
                            $this->customerUtil->addCustomerLedger(
                                voucher_type_id: 5,
                                customer_id: $request->customer_id,
                                branch_id: auth()->user()->branch_id,
                                date: $request->date ?? date('Y-m-d'),
                                trans_id: $customerPayment->id,
                                amount: $dueAmounts
                            );
                        }
                    }
                } elseif ($paidAmount < $request->invoice_payable_amount) {

                    $addPaymentGetId = $this->addPaymentGetId(
                        invoicePrefix: $paymentInvoicePrefix,
                        request: $request,
                        payingAmount: $paidAmount,
                        invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                        saleId: $addSale->id,
                        customerPaymentId: NULL
                    );

                    // Add bank account Ledger
                    $this->accountUtil->addAccountLedger(
                        voucher_type_id: 10,
                        date: $request->date ?? date('Y-m-d'),
                        account_id: $request->account_id,
                        trans_id: $addPaymentGetId,
                        amount: $paidAmount,
                        balance_type: 'debit'
                    );

                    if ($request->customer_id) {

                        $this->customerUtil->addCustomerLedger(
                            voucher_type_id: 3,
                            customer_id: $request->customer_id,
                            branch_id: auth()->user()->branch_id,
                            date: $request->date ?? date('Y-m-d'),
                            trans_id: $addPaymentGetId,
                            amount: $paidAmount
                        );
                    }
                }
            } else {

                $addPaymentGetId = $this->addPaymentGetId(
                    invoicePrefix: $paymentInvoicePrefix,
                    request: $request,
                    payingAmount: $paidAmount,
                    invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                    saleId: $addSale->id,
                    customerPaymentId: NULL
                );

                // Add bank/cash-in-hand A/C ledger
                $this->accountUtil->addAccountLedger(
                    voucher_type_id: 10,
                    date: $request->date ?? date('Y-m-d'),
                    account_id: $request->account_id,
                    trans_id: $addPaymentGetId,
                    amount: $paidAmount,
                    balance_type: 'debit'
                );

                if ($request->customer_id) {

                    $this->customerUtil->addCustomerLedger(
                        voucher_type_id: 3,
                        customer_id: $request->customer_id,
                        branch_id: auth()->user()->branch_id,
                        date: $request->date ?? date('Y-m-d'),
                        trans_id: $addPaymentGetId,
                        amount: $paidAmount
                    );
                }
            }
        }

        if ($addSale->customer_id) {

            $customer = DB::table('customers')->where('id', $addSale->customer_id)->select('total_sale_due')->first();
            $addSale->customer_running_balance = $customer->total_sale_due;
        }

        $addSale->save();
    }

    // Add sale add payment util method
    public function addPaymentGetId($invoicePrefix, $request, $payingAmount, $invoiceId, $saleId, $customerPaymentId)
    {
        $__report_date = '';
        if (isset($request->date)) {

            $__report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        } else {

            $__report_date = date('Y-m-d H:i:s');
        }

        $__invoiceId = str_pad($invoiceId, 5, "0", STR_PAD_LEFT);

        $sale = DB::table('sales')->where('id', $saleId)->select('customer_id')->first();

        $addSalePayment = new SalePayment();
        $addSalePayment->invoice_id = ($invoicePrefix != null ? $invoicePrefix : 'SPV') . str_pad($__invoiceId, 5, "0", STR_PAD_LEFT);
        $addSalePayment->branch_id = auth()->user()->branch_id;
        $addSalePayment->sale_id = $saleId;
        $addSalePayment->customer_id = $sale->customer_id ? $sale->customer_id : NULL;
        $addSalePayment->account_id = $request->account_id;
        $addSalePayment->payment_method_id = $request->payment_method_id;
        $addSalePayment->customer_payment_id = $customerPaymentId;
        $addSalePayment->paid_amount = $payingAmount;
        $addSalePayment->date = $request->date ?? date('d-m-Y');
        $addSalePayment->time = date('h:i:s a');
        $addSalePayment->report_date = $__report_date;
        $addSalePayment->month = date('F');
        $addSalePayment->year = date('Y');
        $addSalePayment->note = $request->payment_note;
        $addSalePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {

            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $addSalePayment->attachment = $salePaymentAttachmentName;
        }

        $addSalePayment->save();

        return $addSalePayment->id;
    }

    public function updatePayment($request, $payment)
    {
        // update sale payment
        $payment->account_id = $payment->customer_payment_id == NULL ? $request->account_id : $payment->account_id;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->paid_amount = $request->paying_amount;
        $payment->date = $request->date;
        $payment->report_date = date('Y-m-d', strtotime($request->date));
        $payment->month = date('F');
        $payment->year = date('Y');
        $payment->note = $request->note;

        if ($request->hasFile('attachment')) {
            if ($payment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                }
            }
            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $payment->attachment = $salePaymentAttachmentName;
        }

        $payment->save();
    }

    public function saleReturnPaymentGetId($request, $sale, $customer_payment_id, $sale_return_id)
    {
        // Add sale return payment
        $addSalePayment = new SalePayment();
        $addSalePayment->invoice_id = 'SRPV' . $this->invoiceVoucherRefIdUtil->getLastId('sale_payments');
        $addSalePayment->sale_id = $sale ? $sale->id : NULL;
        $addSalePayment->branch_id = auth()->user()->branch_id;
        $addSalePayment->sale_return_id = $sale_return_id;
        $addSalePayment->customer_id = $sale ? $sale->customer_id : $request->customer_id;
        $addSalePayment->account_id = $request->account_id;
        $addSalePayment->payment_method_id = $request->payment_method_id;
        $addSalePayment->customer_payment_id = $customer_payment_id;
        $addSalePayment->payment_type = 2;
        $addSalePayment->paid_amount = $request->paying_amount;
        $addSalePayment->date = $request->date;
        $addSalePayment->time = date('h:i:s a');
        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addSalePayment->month = date('F');
        $addSalePayment->year = date('Y');
        $addSalePayment->note = $request->note;
        $addSalePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {

            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $addSalePayment->attachment = $salePaymentAttachmentName;
        }

        $addSalePayment->save();

        return $addSalePayment->id;
    }

    public function updateSaleReturnPayment($request, $payment)
    {
        // update sale payment
        $payment->account_id = $payment->customer_payment_id == NULL ? $request->account_id : $payment->account_id;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->paid_amount = $request->paying_amount;
        $payment->date = $request->date;
        $payment->report_date = date('Y-m-d', strtotime($request->date));
        $payment->month = date('F');
        $payment->year = date('Y');
        $payment->note = $request->note;

        if ($request->hasFile('attachment')) {

            if ($payment->attachment != null) {

                if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {

                    unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                }
            }

            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $payment->attachment = $salePaymentAttachmentName;
        }

        $payment->save();
    }

    public function adjustSaleReturnAmounts($sale_return)
    {
        $saleReturnPayments = DB::table('sale_payments')->where('sale_return_id', $sale_return->id)
            ->select(DB::raw('SUM(paid_amount) as total_paid'))->get();

        $returnDue = $sale_return->total_return_amount - $saleReturnPayments->sum('total_paid');
        $sale_return->total_return_due = $returnDue;
        $sale_return->total_return_due_pay = $saleReturnPayments->sum('total_paid');
        $sale_return->save();
    }

    public function deleteSale($request, $saleId)
    {
        $deleteSale = Sale::with([
            'sale_payments',
            'sale_products',
            'sale_products.purchaseSaleProductChains',
            'sale_products.purchaseSaleProductChains.purchaseProduct',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts',
            'sale_return',
        ])->where('id', $saleId)->first();

        $storedCustomerId = $deleteSale->customer_id;
        $storedSaleAccountId = $deleteSale->sale_account_id;
        $storedSaleReturnAccountId = $deleteSale->sale_return ? $deleteSale->sale_return->sale_return_account_id : NULL;
        $storedBranchId = $deleteSale->branch_id;
        $storedPayments = $deleteSale->sale_payments;
        $storedSaleProducts = $deleteSale->sale_products;
        $storeStatus = $deleteSale->status;

        if ($deleteSale->status == 1 || $deleteSale->status == 3) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: $deleteSale->status == 1 ? 7 : 8, data_obj: $deleteSale);
        }

        $deleteSale->delete();

        if ($storedSaleAccountId) {

            // Adjust sale account balance
            $this->accountUtil->adjustAccountBalance(
                balanceType: 'credit',
                account_id: $storedSaleAccountId
            );
        }

        if ($storedSaleReturnAccountId) {

            // Adjust sale account balance
            $this->accountUtil->adjustAccountBalance(
                balanceType: 'debit',
                account_id: $storedSaleReturnAccountId
            );
        }

        if (count($storedPayments) > 0) {

            foreach ($storedPayments as $payment) {

                if ($payment->attachment) {

                    if (file_exists(public_path('uploads/payment_attachment/' . $payment->attachment))) {

                        unlink(public_path('uploads/payment_attachment/' . $payment->attachment));
                    }
                }

                if ($payment->account_id) {

                    // Adjust Bank/Cash-in-Hand A/C balance
                    $this->accountUtil->adjustAccountBalance(
                        balanceType: 'debit',
                        account_id: $payment->account_id
                    );
                }
            }
        }

        if ($storeStatus == 1) {

            foreach ($storedSaleProducts as $saleProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($saleProduct->product_id, $saleProduct->product_variant_id);

                if ($saleProduct->stock_warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($saleProduct->product_id, $saleProduct->product_variant_id, $saleProduct->stock_warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($saleProduct->product_id, $saleProduct->product_variant_id, $saleProduct->stock_branch_id);
                }


                foreach ($saleProduct->purchaseSaleProductChains as $purchaseSaleProductChain) {

                    if ($purchaseSaleProductChain->purchaseProduct) {

                        $this->purchaseUtil->adjustPurchaseLeftQty($purchaseSaleProductChain->purchaseProduct);
                    }
                }
            }
        }

        if ($storedCustomerId) {

            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($storedCustomerId);
        }

        $count = DB::table('sales')->count();

        if ($count == 0) DB::statement('ALTER TABLE sales AUTO_INCREMENT = 1');
    }

    public function addSaleTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $sales = '';

        $userPermission = auth()->user()->permission;

        $query = DB::table('sales')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id');

        $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.invoice_id',
            'sales.date',
            'sales.total_payable_amount',
            'sales.sale_return_amount',
            'sales.sale_return_due',
            'sales.paid',
            'sales.due',
            'sales.is_return_available',
            'all_total_payable',
            'gross_pay',
            'previous_due',
            'previous_due_paid',
            'customer_running_balance',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $this->filteredQuery($request, $query)->where('sales.status', 1)
                ->where('sales.created_by', 1)
                ->orderBy('sales.report_date', 'desc');
        } else {

            if ($userPermission->sale['view_own_sale'] == '1') {

                $query->where('sales.admin_id', auth()->user()->id);
            }

            $sales = $this->filteredQuery($request, $query)->where('sales.branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)
                ->where('created_by', 1)
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($sales)
            ->addColumn('action', function ($row) use ($userPermission) {

                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> '.__("View").'</a>';

                    if ($userPermission->sale['shipment_access'] == '1') {

                        $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="fas fa-file-alt text-primary"></i> '.__("Packing Slip").'</a>';
                    }

                    if ($userPermission->sale['shipment_access'] == '1') {

                        $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i class="fas fa-truck text-primary"></i> '.__("Edit Shipping").'</a>';
                    }

                    if (auth()->user()->branch_id == $row->branch_id) {

                        // if ($userPermission->sale['sale_payment'] == '1') {

                        //     if ($row->due > 0) {

                        //         $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Payment</a>';
                        //     }
                        // }

                        // if ($userPermission->sale['sale_payment'] == '1') {

                        //     $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal"
                        //     data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i
                        //         class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                        // }

                        // if ($row->sale_return_due > 0) {

                        //     if ($userPermission->sale['sale_payment'] == '1') {

                        //         $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Pay Return Amount</a>';
                        //     }
                        // }

                        if ($userPermission->sale['edit_add_sale'] == '1') {

                            $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Action").'Edit</a>';
                        }

                        if ($userPermission->sale['delete_add_sale'] == '1') {

                            $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                        }
                    }

                    // $html .= '<a class="dropdown-item" id="send_notification" href="' . route('sales.notification.form', [$row->id]) . '"><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';


                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                return $html;
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {

                if ($row->branch_name) {

                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="' . $row->total_payable_amount . '">' . $this->converter->format_in_bdt($row->total_payable_amount) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

            ->editColumn('due', fn ($row) =>  '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')

            ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount" data-value="' . $row->sale_return_amount . '">' . $this->converter->format_in_bdt($row->sale_return_amount) . '</span>')

            ->editColumn('sale_return_due', fn ($row) => '<span class="sale_return_due text-danger" data-value="' . $row->sale_return_due . '">' . $this->converter->format_in_bdt($row->sale_return_due) . '</span>')

            ->editColumn('paid_status', function ($row) {

                $payable = $row->total_payable_amount - $row->sale_return_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })

            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status', 'all_total_payable'])
            ->make(true);
    }

    public function posSaleTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $sales = '';
        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id');

        $query->select(
            'sales.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $this->filteredQuery($request, $query)->where('sales.status', 1)
                ->where('created_by', 2)
                ->orderBy('sales.report_date', 'desc');
        } else {

            if (auth()->user()->permission->sale['view_own_sale'] == '1') {

                $query->where('sales.admin_id', auth()->user()->id);
            }

            $sales = $this->filteredQuery($request, $query)
                ->where('sales.branch_id', auth()->user()->branch_id)
                ->where('created_by', 2)
                ->where('sales.status', 1)
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.pos.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i>'.__("View").' </a>';

                    $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="fas fa-file-alt text-primary"></i> '.__("Packing Slip").'</a>';

                    if (auth()->user()->permission->sale['shipment_access'] == '1') {

                        $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i class="fas fa-truck text-primary"></i> '.__("Edit Shipping").'</a>';
                    }

                    if (auth()->user()->branch_id == $row->branch_id) {

                        // if (auth()->user()->permission->sale['sale_payment'] == '1') {

                        //     if ($row->due > 0) {

                        //         $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Payment</a>';
                        //     }
                        // }

                        // if (auth()->user()->permission->sale['sale_payment'] == '1') {

                        //     $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal" data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                        // }

                        // if ($row->sale_return_due > 0) {

                        //     if (auth()->user()->permission->sale['sale_payment'] == '1') {

                        //         $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('sales.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Pay Return Amount</a>';
                        //     }
                        // }

                        if (auth()->user()->permission->sale['pos_edit'] == '1') {

                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Edit").'</a>';
                        }

                        if (auth()->user()->permission->sale['pos_delete'] == '1') {

                            $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                        }
                    }

                    // $html .= '<a class="dropdown-item" id="items_notification" href=""><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';



                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', fn ($row) => date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date)))
            ->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';
                return $html;
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {

                if ($row->branch_name) {

                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="' . $row->total_payable_amount . '">' . $this->converter->format_in_bdt($row->total_payable_amount) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="due text-danger"  data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')

            ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount text-danger"  data-value="' . $row->sale_return_amount . '">' . $this->converter->format_in_bdt($row->sale_return_amount) . '</span>')

            ->editColumn('sale_return_due', fn ($row) => '<span class="sale_return_due text-danger" data-value="' . $row->sale_return_due . '">' . $this->converter->format_in_bdt($row->sale_return_due) . '</span>')

            ->editColumn('paid_status', function ($row) {

                $payable = $row->total_payable_amount - $row->sale_return_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'sale_return_amount', 'sale_return_due', 'paid_status'])
            ->make(true);
    }

    public function SaleOrderTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $sales = '';

        $userPermission = auth()->user()->permission;

        $query = DB::table('sales')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id');

        $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.invoice_id',
            'sales.date',
            'sales.total_payable_amount',
            'sales.paid',
            'sales.due',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer_name',
        );

        if ($userPermission->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $this->filteredQuery($request, $query)->where('sales.status', 3)
                ->where('sales.created_by', 1)
                ->orderBy('sales.report_date', 'desc');
        } else {

            if ($userPermission->sale['view_own_sale'] == '1') {

                $query->where('sales.admin_id', auth()->user()->id);
            }

            $sales = $this->filteredQuery($request, $query)->where('sales.branch_id', auth()->user()->branch_id)
                ->where('sales.status', 3)
                ->where('created_by', 1)
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($sales)
            ->addColumn('action', function ($row) use ($userPermission) {

                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i>'.__("View").' </a>';

                    if (auth()->user()->branch_id == $row->branch_id) {

                        // if ($userPermission->sale['sale_payment'] == '1') {

                        //     if ($row->due > 0) {

                        //         $html .= '<a class="dropdown-item" id="add_payment" href="' . route('sales.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Payment</a>';
                        //     }
                        // }

                        // if ($userPermission->sale['sale_payment'] == '1') {

                        //     $html .= '<a class="dropdown-item" id="view_payment" data-toggle="modal"
                        //     data-target="#paymentListModal" href="' . route('sales.payment.view', [$row->id]) . '"><i
                        //         class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
                        // }

                        if ($userPermission->sale['edit_add_sale'] == '1') {

                            $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> '.__("Action").'Edit</a>';
                        }

                        if ($userPermission->sale['delete_add_sale'] == '1') {

                            $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                        }
                    }

                    // $html .= '<a class="dropdown-item" id="send_notification" href="' . route('sales.notification.form', [$row->id]) . '"><i class="fas fa-envelope text-primary"></i> New Sale Notification</a>';


                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
                return date($__date_format, strtotime($row->date));
            })

            ->editColumn('from',  function ($row) use ($generalSettings) {

                if ($row->branch_name) {

                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })

            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_payable_amount', fn ($row) => '<span class="total_payable_amount" data-value="' . $row->total_payable_amount . '">' . $this->converter->format_in_bdt($row->total_payable_amount) . '</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

            ->editColumn('due', fn ($row) =>  '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')

            ->editColumn('paid_status', function ($row) {

                $payable = $row->total_payable_amount;
                if ($row->due <= 0) {

                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {

                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })
            ->rawColumns(['action', 'date', 'from', 'customer', 'total_payable_amount', 'paid', 'due', 'paid_status'])
            ->make(true);
    }

    public function soldProductListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $saleProducts = '';
        $query = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.parent_category_id', 'sub_cate.id')
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

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->sub_category_id) {

            $query->where('products.parent_category_id', $request->sub_category_id);
        }

        if ($request->sold_by) {

            $query->where('sales.created_by', $request->sold_by);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }

        $query->select(
            'sale_products.sale_id',
            'sale_products.product_id',
            'sale_products.product_variant_id',
            'sale_products.unit_price_inc_tax',
            'sale_products.quantity',
            'units.code_name as unit_code',
            'sale_products.subtotal',
            'sales.id',
            'sales.date',
            'sales.invoice_id',
            'sales.created_by',
            'products.name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'customers.name as customer_name'
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $saleProducts = $query->orderBy('sales.report_date', 'desc');
        } else {

            if (auth()->user()->permission->sale['view_own_sale'] == '1') {

                $query->where('sales.admin_id', auth()->user()->id);
            }

            $saleProducts = $query->where('sales.branch_id', auth()->user()->branch_id)
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($saleProducts)
            ->editColumn('product', function ($row) {

                $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                return Str::limit($row->name, 25, '') . $variant;
            })->editColumn('sold_by', fn ($row) => $row->created_by == 1 ? '<span class="text-info">ADD SALE</span>' : '<span class="text-success">POS</span>')
            ->editColumn('sku', function ($row) {

                return $row->variant_code ? $row->variant_code : $row->product_code;
            })->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('customer', function ($row) {

                return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
            })->editColumn('invoice_id', fn ($row) => '<a href="' . route('sales.show', [$row->sale_id]) . '" class="details_button text-danger text-hover" title="view" >' . $row->invoice_id . '</a>')
            ->editColumn('quantity', function ($row) {

                return $row->quantity . ' (<span class="qty" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>)';
            })
            ->editColumn('unit_price_inc_tax', fn ($row) => '<span class="unit_price_inc_tax" data-value="' . $row->unit_price_inc_tax . '">' . $this->converter->format_in_bdt($row->unit_price_inc_tax) . '</span>')

            ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->subtotal . '">' . $this->converter->format_in_bdt($row->subtotal) . '</span>')

            ->rawColumns(['product', 'customer', 'invoice_id', 'sku', 'date', 'sold_by', 'quantity', 'branch', 'unit_price_inc_tax', 'subtotal'])

            ->make(true);
    }

    public function saleDraftTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();

        $drafts = '';

        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

        $query->select(
            'sales.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer',
            'admin_and_users.prefix as u_prefix',
            'admin_and_users.name as u_name',
            'admin_and_users.last_name as u_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $drafts = $this->filteredQuery($request, $query)
                ->where('sales.status', 2)
                ->orderBy('sales.report_date', 'desc');
        } else {

            $drafts = $this->filteredQuery($request, $query)->where('branch_id', auth()->user()->branch_id)
                ->where('sales.status', 2)
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($drafts)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.drafts.details', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i>'.__("View").'</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {

                        if ($row->created_by == 1) {

                            $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> '.__("Action").'</a>';
                        } else {

                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i>'.__("Edit").' </a>';
                        }

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt mr-1 text-primary"></i>Delete</a>';
                    }



                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) {

                return date('d/m/Y', strtotime($row->date));
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {

                if ($row->branch_name) {

                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {

                return $row->customer ? $row->customer : 'Walk-In-Customer';
            })
            ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {

                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
            })
            ->editColumn('user', function ($row) {

                return $row->u_prefix . ' ' . $row->u_name . ' ' . $row->u_last_name;
            })
            ->setRowAttr([
                'data-href' => function ($row) {
                    return route('sales.quotations.details', [$row->id]);
                }
            ])
            ->setRowClass('clickable_row')
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'user'])
            ->make(true);
    }

    public function saleQuotationTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $quotations = '';

        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

        $query->select(
            'sales.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer',
            'admin_and_users.prefix as u_prefix',
            'admin_and_users.name as u_name',
            'admin_and_users.last_name as u_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $quotations = $this->filteredQuery($request, $query)->where('sales.status', 4)->orderBy('sales.report_date', 'desc');
        } else {

            $quotations = $this->filteredQuery($request, $query)->where('sales.branch_id', auth()->user()->branch_id)->where('sales.status', 4)->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($quotations)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.quotations.details', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> '.__("View").'</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if ($row->created_by == 1) {
                            $html .= '<a class="dropdown-item" href="' . route('sales.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> '.__("Edit").'</a>';
                        } else {
                            $html .= '<a class="dropdown-item" href="' . route('sales.pos.edit', [$row->id]) . '"><i class="far fa-edit mr-1 text-primary"></i> '.__("Action").'</a>';
                        }
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.delete', [$row->id]) . '"><i class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>';
                    }



                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) {
                return date('d/m/Y', strtotime($row->date));
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer ? $row->customer : 'Walk-In-Customer';
            })
            ->editColumn('total_payable_amount', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_payable_amount . '</b>';
            })
            ->editColumn('user', function ($row) {
                return $row->u_prefix . ' ' . $row->u_name . ' ' . $row->u_last_name;
            })
            ->setRowAttr([
                'data-href' => function ($row) {
                    return route('sales.quotations.details', [$row->id]);
                }
            ])
            ->setRowClass('clickable_row')
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'total_payable_amount', 'user'])
            ->make(true);
    }

    public function saleShipmentListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $sales = '';
        $query = DB::table('sales')->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('admin_and_users', 'sales.admin_id', 'admin_and_users.id');

        $query->select(
            'sales.*',
            'branches.name as branch_name',
            'branches.branch_code',
            'customers.name as customer',
            'admin_and_users.prefix as cr_prefix',
            'admin_and_users.name as cr_name',
            'admin_and_users.last_name as cr_last_name',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $sales = $this->filteredQuery($request, $query)->where('sales.created_by', 1)
                ->where('sales.status', 1)
                ->where('shipment_status', '!=', 'NULL')
                ->orderBy('sales.report_date', 'desc');
        } else {

            $sales = $this->filteredQuery($request, $query)
                ->where('sales.created_by', 1)->where('branch_id', auth()->user()->branch_id)
                ->where('sales.status', 1)
                ->where('shipment_status', '!=', 'NULL')
                ->orderBy('sales.report_date', 'desc');
        }

        return DataTables::of($sales)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.__("Action").'</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('sales.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> '.__("View").'</a>';
                    $html .= '<a class="dropdown-item" id="edit_shipment" href="' . route('sales.shipment.edit', [$row->id]) . '"><i class="fas fa-truck mr-1 text-primary"></i> '.__("Edit shipment").'</a>';
                    $html .= '<a class="dropdown-item" id="print_packing_slip" href="' . route('sales.packing.slip', [$row->id]) . '"><i class="fas fa-file-alt mr-1 text-primary"></i>'.__("Packing Slip").'  </a>';


                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) {
                return date('d/m/Y', strtotime($row->date));
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })
            ->editColumn('customer',  function ($row) {
                return $row->customer ? $row->customer : 'Walk-In-Customer';
            })
            ->editColumn('created_by',  function ($row) {
                return $row->cr_prefix . ' ' . $row->cr_name . ' ' . $row->cr_last_name;
            })
            ->editColumn('shipment_status',  function ($row) {
                $html = "";
                if ($row->shipment_status == 1) {

                    $html .= '<span class="text-primary"><b>Ordered</b></span>';
                } elseif ($row->shipment_status == 2) {

                    $html .= '<span class="text-secondary"><b>Packed</b></span>';
                } elseif ($row->shipment_status == 3) {

                    $html .= '<span class="text-warning"><b>Shipped</b></span>';
                } elseif ($row->shipment_status == 4) {

                    $html .= '<span class="text-success"><b>Delivered</b></span>';
                } elseif ($row->shipment_status == 5) {

                    $html .= '<span class="text-danger"><b>Cancelled</b></span>';
                }
                return $html;
            })
            ->editColumn('paid_status', function ($row) {

                $payable = $row->total_payable_amount - $row->sale_return_amount;
                $html = '';
                if ($row->due <= 0) {

                    $html .= '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {

                    $html .= '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {

                    $html .= '<span class="text-danger"><b>Due</b></span>';
                }
                return $html;
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'customer', 'shipment_status', 'paid_status'])
            ->make(true);
    }

    private function filteredQuery($request, $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', NULL);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('sales.admin_id', $request->user_id);
        }

        if ($request->customer_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sales.customer_id', NULL);
            } else {

                $query->where('sales.customer_id', $request->customer_id);
            }
        }

        if ($request->payment_status) {

            if ($request->payment_status == 1) {

                $query->where('sales.due', '=', 0);
            } else {

                $query->where('sales.due', '>', 0);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }
        return $query;
    }

    public function adjustSaleInvoiceAmounts($sale)
    {
        $totalSalePaid = DB::table('sale_payments')
            ->where('sale_payments.sale_id', $sale->id)->where('payment_type', 1)
            ->select(DB::raw('sum(paid_amount) as total_paid'))
            ->groupBy('sale_payments.sale_id')
            ->get();

        $totalReturnPaid = DB::table('sale_payments')
            ->where('sale_payments.sale_id', $sale->id)->where('payment_type', 2)
            ->select(DB::raw('sum(paid_amount) as total_paid'))
            ->groupBy('sale_payments.sale_id')
            ->get();

        $return = DB::table('sale_returns')->where('sale_id', $sale->id)->first();

        $returnAmount = $return ? $return->total_return_amount : 0;

        $due = $sale->total_payable_amount
            - $totalSalePaid->sum('total_paid')
            - $returnAmount
            + $totalReturnPaid->sum('total_paid');

        $returnDue = $returnAmount
            - ($sale->total_payable_amount - $totalSalePaid->sum('total_paid'))
            - $totalReturnPaid->sum('total_paid');

        $sale->paid = $totalSalePaid->sum('total_paid');
        $sale->due = $due;
        $sale->sale_return_amount = $returnAmount;
        $sale->sale_return_due = $returnDue > 0 ? $returnDue : 0;
        $sale->save();

        return $sale;
    }

    public function addPurchaseSaleProductChain($sale, $stockAccountingMethod)
    {
        foreach ($sale->sale_products as $sale_product) {

            if ($sale_product->product->is_manage_stock == 1) {

                $variant_id = $sale_product->product_variant_id ? $sale_product->product_variant_id : NULL;

                $purchaseProducts = '';

                if ($stockAccountingMethod == '1') {

                    $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                        ->where('product_id', $sale_product->product_id)
                        ->where('product_variant_id',  $variant_id)
                        ->where('branch_id', auth()->user()->branch_id)
                        ->orderBy('created_at', 'asc')->get();
                } else if ($stockAccountingMethod == '2') {

                    $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                        ->where('product_id', $sale_product->product_id)
                        ->where('product_variant_id', $variant_id)
                        ->where('branch_id', auth()->user()->branch_id)
                        ->orderBy('created_at', 'desc')->get();
                }

                if (count($purchaseProducts) > 0) {

                    $sold_qty = $sale_product->quantity;

                    foreach ($purchaseProducts as $purchaseProduct) {

                        if ($sold_qty > $purchaseProduct->left_qty) {

                            if ($sold_qty > 0) {

                                $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                                $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                                $addPurchaseSaleChain->save();
                                $sold_qty -= $purchaseProduct->left_qty;
                                $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                            } else {

                                break;
                            }
                        } else if ($sold_qty == $purchaseProduct->left_qty) {

                            if ($sold_qty > 0) {

                                $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                                $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                                $addPurchaseSaleChain->save();
                                $sold_qty -= $purchaseProduct->left_qty;
                                $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                            } else {

                                break;
                            }
                        } else if ($sold_qty < $purchaseProduct->left_qty) {

                            if ($sold_qty > 0) {

                                $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                                $addPurchaseSaleChain->sold_qty = $sold_qty;
                                $addPurchaseSaleChain->save();
                                $sold_qty -= $sold_qty;
                                $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                            } else {

                                break;
                            }
                        }
                    }
                }
            } else {

                $addPurchaseSaleChain = new PurchaseSaleProductChain();
                $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                $addPurchaseSaleChain->sold_qty = $sale_product->quantity;
                $addPurchaseSaleChain->save();
            }
        }
    }

    public function updatePurchaseSaleProductChain($sale, $stockAccountingMethod)
    {
        foreach ($sale->sale_products as $sale_product) {

            if ($sale_product->product->is_manage_stock == 1) {

                $variant_id = $sale_product->product_variant_id ? $sale_product->product_variant_id : NULL;

                $sold_qty = $sale_product->quantity;

                $salePurchaseProductChains = PurchaseSaleProductChain::with('purchaseProduct')
                    ->where('sale_product_id', $sale_product->id)->get();

                foreach ($salePurchaseProductChains as $salePurchaseProductChain) {

                    $salePurchaseProductChain->purchaseProduct->left_qty += $salePurchaseProductChain->sold_qty;
                    $salePurchaseProductChain->purchaseProduct->save();

                    if ($sold_qty > $salePurchaseProductChain->purchaseProduct->left_qty) {

                        //$dist_qty = $salePurchaseProductChain->purchaseProduct->left_qty;
                        $salePurchaseProductChain->sold_qty = $salePurchaseProductChain->purchaseProduct->left_qty;
                        $salePurchaseProductChain->save();
                        $sold_qty = $sold_qty - $salePurchaseProductChain->purchaseProduct->left_qty;
                        $this->purchaseUtil->adjustPurchaseLeftQty($salePurchaseProductChain->purchaseProduct);
                    } elseif ($sold_qty == $salePurchaseProductChain->purchaseProduct->left_qty) {

                        //$dist_qty = $salePurchaseProductChain->purchaseProduct->left_qty;
                        $salePurchaseProductChain->sold_qty = $salePurchaseProductChain->purchaseProduct->left_qty;
                        $salePurchaseProductChain->save();
                        $sold_qty = $sold_qty - $salePurchaseProductChain->purchaseProduct->left_qty;
                        $this->purchaseUtil->adjustPurchaseLeftQty($salePurchaseProductChain->purchaseProduct);
                    } elseif ($sold_qty < $salePurchaseProductChain->purchaseProduct->left_qty) {

                        //$dist_qty = $sold_qty;
                        $salePurchaseProductChain->sold_qty = $sold_qty;
                        $salePurchaseProductChain->save();
                        $sold_qty = $sold_qty - $sold_qty;
                        $this->purchaseUtil->adjustPurchaseLeftQty($salePurchaseProductChain->purchaseProduct);
                    }
                }

                if ($sold_qty > 0) {

                    $purchaseProducts = '';
                    if ($stockAccountingMethod == '1') {

                        $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                            ->where('product_id', $sale_product->product_id)
                            ->where('product_variant_id',  $variant_id)
                            ->where('branch_id', auth()->user()->branch_id)
                            ->orderBy('created_at', 'asc')->get();
                    } else if ($stockAccountingMethod == '2') {

                        $purchaseProducts = PurchaseProduct::where('left_qty', '>', '0')
                            ->where('product_id', $sale_product->product_id)
                            ->where('product_variant_id', $variant_id)
                            ->where('branch_id', auth()->user()->branch_id)
                            ->orderBy('created_at', 'desc')->get();
                    }

                    if (count($purchaseProducts) > 0) {

                        foreach ($purchaseProducts as $purchaseProduct) {

                            if ($sold_qty > $purchaseProduct->left_qty) {

                                if ($sold_qty > 0) {

                                    $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                    $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                    $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                                    $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                                    $addPurchaseSaleChain->save();
                                    $sold_qty -= $purchaseProduct->left_qty;
                                    $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } else if ($sold_qty == $purchaseProduct->left_qty) {

                                if ($sold_qty > 0) {

                                    $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                    $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                    $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                                    $addPurchaseSaleChain->sold_qty = $purchaseProduct->left_qty;
                                    $addPurchaseSaleChain->save();
                                    $sold_qty -= $purchaseProduct->left_qty;
                                    $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            } else if ($sold_qty < $purchaseProduct->left_qty) {

                                if ($sold_qty > 0) {

                                    $addPurchaseSaleChain = new PurchaseSaleProductChain();
                                    $addPurchaseSaleChain->purchase_product_id = $purchaseProduct->id;
                                    $addPurchaseSaleChain->sale_product_id = $sale_product->id;
                                    $addPurchaseSaleChain->sold_qty = $sold_qty;
                                    $addPurchaseSaleChain->save();
                                    $sold_qty -= $sold_qty;
                                    $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                                } else {

                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function getStockLimitProducts($sale)
    {
        $qty_limits = [];

        foreach ($sale->sale_products as $sale_product) {

            if ($sale_product->product->is_manage_stock == 0) {

                $qty_limits[] = PHP_INT_MAX;
            } else {

                $productBranch = DB::table('product_branches')->where('branch_id', $sale->branch_id)
                    ->where('product_id', $sale_product->product_id)->first();

                if ($sale_product->product->type == 2) {

                    $qty_limits[] = 500000;
                } elseif ($sale_product->product_variant_id) {

                    $productBranchVariant = DB::table('product_branch_variants')
                        ->where('product_branch_id', $productBranch->id)
                        ->where('product_id', $sale_product->product_id)
                        ->where('product_variant_id', $sale_product->product_variant_id)
                        ->first();

                    $qty_limits[] = $productBranchVariant->variant_quantity;
                } else {

                    $qty_limits[] = $productBranch->product_quantity;
                }
            }
        }

        return $qty_limits;
    }

    public function customerCopySaleProductsQuery($saleId)
    {
        return DB::table('sale_products')
            ->where('sale_products.sale_id', $saleId)
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('warranties', 'products.warranty_id', 'warranties.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->select(
                'sale_products.product_id',
                'sale_products.product_variant_id',
                'sale_products.description',
                'sale_products.unit',
                // 'sale_products.quantity',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit_price_exc_tax',
                'sale_products.unit_discount_amount',
                'sale_products.unit_tax_percent',
                'sale_products.unit_tax_amount',
                'sale_products.subtotal',
                // 'sale_products.subtotal',
                'products.name as p_name',
                'products.product_code',
                'products.warranty_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'warranties.duration as w_duration',
                'warranties.duration_type as w_duration_type',
                'warranties.description as w_description',
                'warranties.type as w_type',
                DB::raw('SUM(sale_products.quantity) as quantity'),
                DB::raw('SUM(sale_products.subtotal) as subtotal'),
            )
            ->groupBy('sale_products.product_id')
            ->groupBy('sale_products.product_variant_id')
            ->groupBy('sale_products.description')
            ->groupBy('sale_products.unit')
            // ->groupBy('sale_products.quantity')
            ->groupBy('sale_products.unit_price_inc_tax')
            ->groupBy('sale_products.unit_price_exc_tax')
            ->groupBy('sale_products.unit_discount_amount')
            ->groupBy('sale_products.unit_tax_percent')
            ->groupBy('sale_products.unit_tax_amount')
            ->groupBy('sale_products.subtotal')
            // ->groupBy('sale_products.subtotal')
            ->groupBy('products.warranty_id')
            ->groupBy('products.name')
            ->groupBy('products.product_code')
            ->groupBy('warranties.duration')
            ->groupBy('warranties.duration_type')
            ->groupBy('warranties.type')
            ->groupBy('warranties.description')
            ->groupBy('product_variants.variant_name')
            ->groupBy('product_variants.variant_code')
            ->get();
    }


    public static function saleStatus()
    {
        return [
            1 => 'Final',
            3 => 'Ordered',
            2 => 'Draft',
            4 => 'Quotation',
        ];
    }

    public static function saleShipmentStatus()
    {
        return [
            1 => 'Ordered',
            2 => 'Packed',
            3 => 'Shipped',
            4 => 'Delivered',
            5 => 'Cancelled',
        ];
    }
}
