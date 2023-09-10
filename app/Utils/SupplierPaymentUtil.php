<?php

namespace App\Utils;

use App\Models\Purchase;
use App\Utils\PurchaseUtil;
use App\Models\PurchasePayment;
use App\Models\SupplierPaymentInvoice;
use App\Utils\InvoiceVoucherRefIdUtil;

class SupplierPaymentUtil
{
    public $purchaseUtil;
    public $invoiceVoucherRefIdUtil;

    public function __construct(PurchaseUtil $purchaseUtil, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,)
    {
        $this->purchaseUtil = $purchaseUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function specificPurchaseOrOrderByPayment($request, $supplierPayment, $supplierId, $paymentInvoicePrefix)
    {
        $dueInvoices = Purchase::where('supplier_id', $supplierId)
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('id', $request->purchase_ids)
            ->orderBy('report_date', 'asc')
            ->get();

        if (count($dueInvoices) > 0) {

            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {

                if ($dueInvoice->due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                        //$dueAmounts -= $dueAmounts;
                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $request->paying_amount) {

                    if ($dueInvoice->due > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $dueInvoice->due);

                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $dueInvoice->due);

                        // Calculate next payment amount
                        $request->paying_amount -= $dueInvoice->due;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                }
                $index++;
            }
        }

        if ($request->paying_amount > 0) {

            $dueInvoices = Purchase::where('supplier_id', $supplierId)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('due', '>', 0)
                ->orderBy('report_date', 'asc')
                ->get();

            if (count($dueInvoices) > 0) {

                $index = 0;
                foreach ($dueInvoices as $dueInvoice) {

                    if ($dueInvoice->due > $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                            $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                            //$dueAmounts -= $dueAmounts;
                            $request->paying_amount -= $request->paying_amount;
                            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                        }
                    } elseif ($dueInvoice->due == $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                            $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                            $request->paying_amount -= $request->paying_amount;
                            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                        }
                    } elseif ($dueInvoice->due < $request->paying_amount) {

                        if ($dueInvoice->due > 0) {

                            $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $dueInvoice->due);

                            $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $dueInvoice->due);

                            // Calculate next payment amount
                            $request->paying_amount -= $dueInvoice->due;
                            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                        }
                    }

                    $index++;
                }
            }
        }
    }

    public function randomPurchaseOrOrderPayment($request, $supplierPayment, $supplierId, $paymentInvoicePrefix)
    {
        $dueInvoices = Purchase::where('supplier_id', $supplierId)
            ->where('branch_id', auth()->user()->branch_id)
            ->where('due', '>', 0)
            ->orderBy('report_date', 'asc')
            ->get();

        if (count($dueInvoices) > 0) {

            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {

                if ($dueInvoice->due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);
                        //$dueAmounts -= $dueAmounts;
                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        // Add Supplier Payment invoice
                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $request->paying_amount) {

                    if ($dueInvoice->due > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $dueInvoice->due);

                        // Add Supplier Payment invoice
                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $dueInvoice->due);

                        // Calculate next payment amount
                        $request->paying_amount -= $dueInvoice->due;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                }

                $index++;
            }
        }
    }

    public function purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $payingAmount)
    {
        $addPurchasePayment = new PurchasePayment();
        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);
        $addPurchasePayment->purchase_id = $dueInvoice->id;
        $addPurchasePayment->branch_id = auth()->user()->branch_id;
        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
        $addPurchasePayment->account_id = $request->account_id;
        $addPurchasePayment->paid_amount = $payingAmount;
        $addPurchasePayment->date = $request->date;
        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchasePayment->month = date('F');
        $addPurchasePayment->year = date('Y');
        $addPurchasePayment->payment_method_id = $request->payment_method_id;
        $addPurchasePayment->admin_id = auth()->user()->id;
        $addPurchasePayment->payment_on = 1;
        $addPurchasePayment->save();
    }

    public function supplierPaymentInvoice($supplierPayment, $dueInvoice, $payingAmount)
    {
        // Add Supplier Payment invoice
        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
        $addSupplierPaymentInvoice->purchase_id = $dueInvoice->id;
        $addSupplierPaymentInvoice->paid_amount = $payingAmount;
        $addSupplierPaymentInvoice->save();
    }
}
