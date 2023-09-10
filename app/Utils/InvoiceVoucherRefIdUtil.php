<?php
namespace App\Utils;

use Illuminate\Support\Facades\DB;

class InvoiceVoucherRefIdUtil
{
    public function customerPaymentVoucherNo()
    {
        $id = 1;
        $lastCustomerPayment = DB::table('customer_payments')->orderBy('id', 'desc')->first(['id']);
        if ($lastCustomerPayment) {
            $id = ++$lastCustomerPayment->id;
        }
        return $id;
    }

    public function salePaymentVoucherNo()
    {
        $id = 1;
        $lastSalePayment = DB::table('sale_payments')->orderBy('id', 'desc')->first(['id']);
        if ($lastSalePayment) {
            $id = ++$lastSalePayment->id;
        }
        return $id;
    }

    public function supplierPaymentVoucherNo()
    {
        $id = 1;
        $lastSupplierPayment = DB::table('supplier_payments')->orderBy('id', 'desc')->first(['id']);
        if ($lastSupplierPayment) {
            $id = ++$lastSupplierPayment->id;
        }
        return $id;
    }

    public function purchasePaymentVoucherNo()
    {
        $id = 1;
        $lastPurchasePayment = DB::table('purchase_payments')->orderBy('id', 'desc')->first(['id']);
        if ($lastPurchasePayment) {
            $id = ++$lastPurchasePayment->id;
        }
        return $id;
    }

    public function getLastId($table)
    {
        $id = 1;
        $lastEntry = DB::table($table)->orderBy('id', 'desc')->first(['id']);
        if ($lastEntry) {
            $id = ++$lastEntry->id;
        }
        return $id;
    }
}