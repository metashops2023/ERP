<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BranchWiseCustomerAmountUtil
{
    public function branchWiseCustomerAmount($customerId, $branch_id = null, $from_date = null, $to_date = null)
    {
        $openingBalanceDetails = DB::table('customer_opening_balances')->where('customer_opening_balances.customer_id', $customerId)
            ->where('customer_opening_balances.branch_id', ($branch_id == 'NULL' ? NULL : $branch_id))
            ->select('customer_opening_balances.amount', 'customer_opening_balances.is_show_again')
            ->first();

        $customer = DB::table('customers')->where('id', $customerId)->select('id', 'point')->first();

        $amounts = '';

        $query = DB::table('customer_ledgers')
            ->where('customer_ledgers.customer_id', $customerId)
            ->leftJoin('customer_payments', 'customer_ledgers.customer_payment_id', 'customer_payments.id');

        if ($branch_id) {

            if ($branch_id == 'NULL') {

                $query->where('customer_ledgers.branch_id', NULL);
            } else {

                $query->where('customer_ledgers.branch_id', $branch_id);
            }
        }

        if ($from_date) {

            $from_date = date('Y-m-d', strtotime($from_date));
            $to_date = $to_date ? date('Y-m-d', strtotime($to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('customer_ledgers.report_date', $date_range); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $query->select(
                'voucher_type',
                DB::raw('SUM(amount) as amt'),
                DB::raw('SUM(customer_payments.less_amount) as less_amt'),
            )->groupBy('customer_ledgers.voucher_type');
        } else {

            $query->where('customer_ledgers.branch_id', auth()->user()->branch_id)
                ->select(
                    'voucher_type',
                    DB::raw('SUM(amount) as amt'),
                    DB::raw('SUM(customer_payments.less_amount) as less_amt'),
                )->groupBy('customer_ledgers.voucher_type');
        }

        $amounts = $query->get();

        $openingBalance = 0;
        $totalSaleAndOrder = 0;
        $totalPaid = 0;
        $totalReturn = 0;
        $totalLess = 0;
        $totalRefund = 0;

        foreach ($amounts as $amount) {

            if ($amount->voucher_type == 0) {

                $openingBalance += $amount->amt;
            } elseif ($amount->voucher_type == 1) {

                $totalSaleAndOrder += $amount->amt;
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

        $totalDue = ($totalSaleAndOrder + $openingBalance + $totalRefund) - $totalPaid - $totalReturn - $totalLess;

        $totalReturnDue = $totalReturn - ($totalSaleAndOrder + $openingBalance - $totalPaid) - $totalRefund;

        return [
            'opening_balance' => $openingBalance,
            'total_sale' => $totalSaleAndOrder,
            'total_paid' => $totalPaid,
            'total_return' => $totalReturn,
            'total_less' => $totalLess,
            'totalRefund' => $totalRefund,
            'total_sale_due' => $totalDue,
            'total_sale_return_due' => $totalReturnDue > 0 ? $totalReturnDue : 0,
            'openingBalanceDetails' => $openingBalanceDetails,
            'point' => $customer->point,
        ];
    }

    public function branchWiseCustomerInvoiceAndOrders($customer_id, $branch_id = null)
    {
        $allSalesAndOrders = '';
        $invoices = '';
        $orders = '';

        $allSalesAndOrdersQuery = DB::table('sales')->where('sales.customer_id', $customer_id)
            ->whereIn('sales.status', [1, 3])
            ->where('sales.due', '>', 0);

        $invoicesQuery = DB::table('sales')
            ->where('sales.customer_id', $customer_id)
            ->where('sales.status', 1)->where('sales.due', '>', 0);

        $ordersQuery = DB::table('sales')->where('sales.customer_id', $customer_id)
            ->where('sales.status', 3)->where('sales.due', '>', 0);

        if ($branch_id) {

            if ($branch_id == 'NULL') {

                $allSalesAndOrdersQuery->where('sales.branch_id', NULL);
                $invoicesQuery->where('sales.branch_id', NULL);
                $ordersQuery->where('sales.branch_id', NULL);
            } else {

                $allSalesAndOrdersQuery->where('sales.branch_id', $branch_id)->where('sales.due', '>', 0);
                $invoicesQuery->where('sales.branch_id', $branch_id);
                $ordersQuery->where('sales.branch_id', $branch_id);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $allSalesAndOrdersQuery->select('id', 'date', 'invoice_id', 'total_payable_amount', 'sale_return_amount', 'due', 'status')->orderBy('report_date', 'desc');

            $invoicesQuery->select('id', 'date', 'invoice_id', 'total_payable_amount', 'sale_return_amount', 'due')->orderBy('report_date', 'desc');

            $ordersQuery->select('id', 'date', 'invoice_id', 'total_payable_amount', 'sale_return_amount', 'due', 'status')
                ->orderBy('report_date', 'desc');
        } else {

            $allSalesAndOrdersQuery->where('sales.branch_id', auth()->user()->branch_id)->select('id', 'date', 'invoice_id', 'total_payable_amount', 'sale_return_amount', 'due', 'status')->orderBy('report_date', 'desc');

            $invoicesQuery->where('sales.branch_id', auth()->user()->branch_id)->select('id', 'date', 'invoice_id', 'total_payable_amount', 'sale_return_amount', 'due')->orderBy('report_date', 'desc');

            $ordersQuery->where('sales.branch_id', auth()->user()->branch_id)->select('id', 'date', 'invoice_id', 'total_payable_amount', 'sale_return_amount', 'due', 'status')->orderBy('report_date', 'desc');
        }

        $allSalesAndOrders = $allSalesAndOrdersQuery->get();
        $invoices = $invoicesQuery->get();
        $orders = $ordersQuery->get();

        return [
            'allSalesAndOrders' => $allSalesAndOrders,
            'invoices' => $invoices,
            'orders' => $orders,
        ];
    }
}
