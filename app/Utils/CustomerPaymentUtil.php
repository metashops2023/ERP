<?php

namespace App\Utils;

use App\Models\Sale;
use App\Utils\SaleUtil;
use App\Models\SalePayment;
use App\Models\CustomerPaymentInvoice;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Support\Facades\Log;

class CustomerPaymentUtil
{
    public $saleUtil;
    public $invoiceVoucherRefIdUtil;

    public function __construct(SaleUtil $saleUtil, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,)
    {
        $this->saleUtil = $saleUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function specificInvoiceOrOrderByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix)
    {
        $dueInvoices = Sale::where('customer_id', $customerId)
            ->whereIn('id', $request->sale_ids)
            ->orderBy('report_date', 'asc')
            ->get();

        if (count($dueInvoices) > 0) {

            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {

                if ($dueInvoice->due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $request->paying_amount);

                        $request->paying_amount -= $request->paying_amount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $request->paying_amount);

                        $request->paying_amount -= $request->paying_amount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $request->paying_amount) {

                    if ($dueInvoice->due > 0) {

                        $this->saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $dueInvoice->due);

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $dueInvoice->due);

                        $request->paying_amount -= $dueInvoice->due;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                }

                $index++;
            }
        }

        if ($request->paying_amount > 0) {

            $dueInvoices = Sale::where('customer_id', $customerId)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('due', '>', 0)
                ->orderBy('report_date', 'asc')
                ->get();

            if (count($dueInvoices) > 0) {

                $index = 0;
                foreach ($dueInvoices as $dueInvoice) {

                    if ($dueInvoice->due > $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $this->saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                            // Add Customer Payment invoice
                            $this->customerPaymentInvoice($customerPayment, $dueInvoice, $request->paying_amount);

                            $request->paying_amount -= $request->paying_amount;
                            $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                        }
                    } elseif ($dueInvoice->due == $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $this->saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                            // Add Customer Payment invoice
                            $this->customerPaymentInvoice($customerPayment, $dueInvoice, $request->paying_amount);

                            $request->paying_amount -= $request->paying_amount;
                            $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                        }
                    } elseif ($dueInvoice->due < $request->paying_amount) {

                        if ($dueInvoice->due > 0) {

                            $this->saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $dueInvoice->due);

                            // Add Customer Payment invoice
                            $this->customerPaymentInvoice($customerPayment, $dueInvoice, $dueInvoice->due);

                            $request->paying_amount -= $dueInvoice->due;
                            $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                        }
                    }

                    $index++;
                }
            }
        }
    }

    public function randomInvoiceOrSalesOrderPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix)
    {
        $dueInvoices = Sale::where('customer_id', $customerId)
            ->where('branch_id', auth()->user()->branch_id)
            ->where('due', '>', 0)
            ->orderBy('report_date', 'asc')
            ->get();

        if (count($dueInvoices) > 0) {

            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {

                if ($dueInvoice->due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $request->paying_amount);

                        $request->paying_amount -= $request->paying_amount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $request->paying_amount);

                        $request->paying_amount -= $request->paying_amount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $request->paying_amount) {

                    if ($dueInvoice->due > 0) {

                        $this->saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $dueInvoice->due);

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $dueInvoice->due);

                        $request->paying_amount -= $dueInvoice->due;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                }

                $index++;
            }
        }
    }

    public function saleOrSalesOrderFillUpByPayment($request, $customerPayment, $customerId, $paymentInvoicePrefix, $dueInvoice, $payingAmount)
    {
        $addSalePayment = new SalePayment();
        $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('sale_payments'), 5, "0", STR_PAD_LEFT);
        $addSalePayment->branch_id = auth()->user()->branch_id;
        $addSalePayment->sale_id = $dueInvoice->id;
        $addSalePayment->customer_id = $customerId;
        $addSalePayment->account_id = $request->account_id;
        $addSalePayment->customer_payment_id = $customerPayment->id;
        $addSalePayment->paid_amount = $payingAmount;
        $addSalePayment->date = date('d-m-Y', strtotime($request->date));
        $addSalePayment->time = date('h:i:s a');
        $addSalePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addSalePayment->month = date('F');
        $addSalePayment->year = date('Y');
        $addSalePayment->payment_method_id = $request->payment_method_id;
        $addSalePayment->admin_id = auth()->user()->id;
        $addSalePayment->payment_on = 1;
        $addSalePayment->save();
    }

    public function customerPaymentInvoice($customerPayment, $dueInvoice, $payingAmount)
    {
        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
        $addCustomerPaymentInvoice->sale_id = $dueInvoice->id;
        $addCustomerPaymentInvoice->paid_amount = $payingAmount;
        $addCustomerPaymentInvoice->save();
    }
}
