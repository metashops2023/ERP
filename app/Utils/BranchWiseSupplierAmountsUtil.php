<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BranchWiseSupplierAmountsUtil
{
    public function branchWiseSupplierAmount($supplierId, $branch_id = null, $from_date = null, $to_date = null)
    {
        $openingBalanceDetails = DB::table('supplier_opening_balances')->where('supplier_opening_balances.supplier_id', $supplierId)
            ->where('supplier_opening_balances.branch_id', ($branch_id == 'NULL' ? NULL : $branch_id))
            ->select('supplier_opening_balances.amount', 'supplier_opening_balances.is_show_again')
            ->first();

        $amounts = '';

        $query = DB::table('supplier_ledgers')
            ->where('supplier_ledgers.supplier_id', $supplierId)
            ->leftJoin('supplier_payments', 'supplier_ledgers.supplier_payment_id', 'supplier_payments.id');

        if ($branch_id) {

            if ($branch_id == 'NULL') {

                $query->where('supplier_ledgers.branch_id', NULL);
            } else {

                $query->where('supplier_ledgers.branch_id', $branch_id);
            }
        }

        if ($from_date) {

            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = $to_date ? date('Y-m-d', strtotime($to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('supplier_ledgers.report_date', $date_range); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $query->select(
                'voucher_type',
                DB::raw('SUM(amount) as amt'),
                DB::raw('SUM(supplier_payments.less_amount) as less_amt'),
            )->groupBy('supplier_ledgers.voucher_type');
        } else {

            $query->where('supplier_ledgers.branch_id', auth()->user()->branch_id)
                ->select(
                    'voucher_type',
                    DB::raw('SUM(amount) as amt'),
                    DB::raw('SUM(supplier_payments.less_amount) as less_amt'),
                )->groupBy('supplier_ledgers.voucher_type');
        }

        $amounts = $query->get();

        $openingBalance = 0;
        $totalPurchaseAndOrder = 0;
        $totalPaid = 0;
        $totalReturn = 0;
        $totalLess = 0;
        $totalRefund = 0;

        foreach ($amounts as $amount) {

            if ($amount->voucher_type == 0) {

                $openingBalance += $amount->amt;
            } elseif ($amount->voucher_type == 1) {

                $totalPurchaseAndOrder += $amount->amt;
            } elseif ($amount->voucher_type == 2) {

                $totalReturn += $amount->amt;
            } elseif ($amount->voucher_type == 3) {

                $totalPaid += $amount->amt;
            } elseif ($amount->voucher_type == 4) {

                $totalRefund += $amount->amt;
            } elseif ($amount->voucher_type == 5) {

                $totalPaid += $amount->amt;
                $totalLess += $amount->less_amt;
            } elseif ($amount->voucher_type == 6) {

                $totalRefund += $amount->amt;
            }
        }

        $totalDue = ($totalPurchaseAndOrder + $openingBalance + $totalRefund) - $totalPaid - $totalReturn - $totalLess;

        $totalReturnDue = $totalReturn - ($totalPurchaseAndOrder + $openingBalance - $totalPaid) - $totalRefund;

        return [
            'opening_balance' => $openingBalance,
            'total_purchase' => $totalPurchaseAndOrder,
            'total_paid' => $totalPaid,
            'total_return' => $totalReturn,
            'total_less' => $totalLess,
            'total_refund' => $totalRefund,
            'total_purchase_due' => $totalDue,
            'total_purchase_return_due' => $totalReturnDue > 0 ? $totalReturnDue : 0,
            'openingBalanceDetails' => $openingBalanceDetails,
        ];
    }

    public function branchWiseSupplierPurchasesAndOrders($supplier_id, $branch_id = null)
    {
        $allPurchasesAndOrders = '';
        $purchases = '';
        $orders = '';

        $allPurchasesAndOrdersQuery = DB::table('purchases')->where('purchases.supplier_id', $supplier_id)
            ->whereIn('purchases.purchase_status', [1, 3])
            ->where('purchases.due', '>', 0);

        $purchasesQuery = DB::table('purchases')
            ->where('purchases.supplier_id', $supplier_id)
            ->where('purchases.purchase_status', 1)->where('purchases.due', '>', 0);

        $ordersQuery = DB::table('purchases')->where('purchases.supplier_id', $supplier_id)
            ->where('purchases.purchase_status', 3)->where('purchases.due', '>', 0);

        if ($branch_id) {

            if ($branch_id == 'NULL') {

                $allPurchasesAndOrdersQuery->where('purchases.branch_id', NULL);
                $purchasesQuery->where('purchases.branch_id', NULL);
                $ordersQuery->where('purchases.branch_id', NULL);
            } else {

                $allPurchasesAndOrdersQuery->where('purchases.branch_id', $branch_id);
                $purchasesQuery->where('purchases.branch_id', $branch_id);
                $ordersQuery->where('purchases.branch_id', $branch_id);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $allPurchasesAndOrdersQuery->select('id', 'invoice_id', 'date', 'due', 'total_purchase_amount', 'purchase_return_amount', 'purchase_status')->orderBy('purchases.report_date', 'desc');

            $purchasesQuery->select('id', 'invoice_id', 'date', 'total_purchase_amount', 'purchase_return_amount', 'purchase_return_amount', 'due')->orderBy('purchases.report_date', 'desc');

            $ordersQuery->select('id', 'invoice_id', 'date', 'total_purchase_amount', 'purchase_return_amount', 'due')
                ->orderBy('purchases.report_date', 'desc');
        } else {

            $allPurchasesAndOrdersQuery->where('purchases.branch_id', auth()->user()->branch_id)
                ->select('id', 'invoice_id', 'date', 'due', 'total_purchase_amount', 'purchase_return_amount', 'purchase_status')
                ->orderBy('purchases.report_date', 'desc');

            $purchasesQuery->where('purchases.branch_id', auth()->user()->branch_id)
                ->select('id', 'invoice_id', 'date', 'total_purchase_amount', 'purchase_return_amount', 'purchase_return_amount', 'due')
                ->orderBy('purchases.report_date', 'desc');

            $ordersQuery->where('purchases.branch_id', auth()->user()->branch_id)
                ->select('id', 'invoice_id', 'date', 'total_purchase_amount', 'purchase_return_amount', 'due')
                ->orderBy('purchases.report_date', 'desc');
        }

        $allPurchasesAndOrders = $allPurchasesAndOrdersQuery->get();
        $purchases = $purchasesQuery->get();
        $orders = $ordersQuery->get();

        return [
            'allPurchasesAndOrders' => $allPurchasesAndOrders,
            'purchases' => $purchases,
            'orders' => $orders,
        ];
    }
}
