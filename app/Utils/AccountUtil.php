<?php

namespace App\Utils;

use App\Models\Account;
use App\Models\AccountLedger;
use Illuminate\Support\Facades\DB;

class AccountUtil
{
    // public function adjustAccountBalanceTest($account_id)
    // {
    //     $cashFlowD = DB::table('cash_flows')->where('cash_type', 1)
    //         ->where('account_id', $account_id)
    //         ->where('debit', '!=', NULL)
    //         ->select(DB::raw('sum(debit) as t_debit'))
    //         ->get();

    //     $totalDebit = $cashFlowD->sum('t_debit') ? $cashFlowD->sum('t_debit') : 0;

    //     $expenseLoan = DB::table('cash_flows')
    //         ->where('cash_flows.account_id', $account_id)
    //         ->where('loan_id', '!=', NULL)
    //         ->where('debit', '!=', NULL)
    //         ->leftJoin('loans', 'cash_flows.loan_id', 'loans.id')
    //         ->where('loans.loan_by', 'Expense')->select(DB::raw('sum(credit) as t_debit'))
    //         ->groupBy('loans.loan_by')
    //         ->get();

    //     $totalExpenseLoan = $expenseLoan->sum('t_debit') ? $expenseLoan->sum('t_debit') : 0;

    //     $acDebit = $totalDebit - $totalExpenseLoan;

    //     $cashFlowC = DB::table('cash_flows')->where('cash_type', 2)
    //         ->where('credit', '!=', NULL)
    //         ->where('cash_flows.account_id', $account_id)
    //         ->select(DB::raw('sum(credit) as t_credit'))
    //         ->get();

    //     $totalCredit = $cashFlowC->sum('t_credit') ? $cashFlowC->sum('t_credit') : 0;

    //     $account = Account::where('id', $account_id)->first();
    //     $account->debit = $acDebit;
    //     $account->credit = $totalCredit;
    //     $account->balance = $acDebit - $totalCredit;
    //     $account->save();
    //     return $account->balance;
    // }

    public function adjustAccountBalance($balanceType, $account_id)
    {
        $ac_ledger = DB::table('account_ledgers')
            ->where('account_ledgers.account_id', $account_id)
            ->select(
                DB::raw('sum(debit) as t_debit'),
                DB::raw('sum(credit) as t_credit')
            )->groupBy('account_ledgers.account_id')->get();

        $currentBalance = 0;
        if ($balanceType == 'debit') {

            $currentBalance = $ac_ledger->sum('t_debit') - $ac_ledger->sum('t_credit');
        } else if ($balanceType == 'credit') {

            $currentBalance = $ac_ledger->sum('t_credit') - $ac_ledger->sum('t_debit');
        }

        $account = Account::where('id', $account_id)->first();
        $account->debit = $ac_ledger->sum('t_debit');
        $account->credit = $ac_ledger->sum('t_credit');
        $account->balance = $currentBalance;
        $account->save();
        return $account->balance;
    }

    public static function creatableDefaultAccount()
    {
        return [
            1 => ['Cash',],
            3 => ['Purchase',],
            4 => ['Purchase Return',],
            5 => ['Sales',],
            6 => ['Sales Return',],
            7 => ['Expense', 'Office Expense', 'Cartage', 'Buy Goods',],
            8 => ['Advertisement Expenses', 'Rent Paid',],
            9 => ['Current Asset'],
            10 => ['Current Liability', 'Salary Payable', 'Tax Deducted Payable',],
            13 => ['Loan Liabilities',],
            14 => ['Loan&Advances', 'Advance Salary',],
            15 => ['Furniture', 'Vehicle',],
            22 => ['Stock Adjustment',],
            23 => ['Production'],
            24 => ['Income', 'Discount On Purchase', 'Discount Received',],
            25 => ['Interest Received'],
            26 => ['Capital'],
            // 26 => 'Profit & Loss A/C',
        ];
    }

    public static function voucherTypes()
    {
        return [
            0 => 'Opening balance',
            1 => 'Sales',
            2 => 'Sale Return',
            3 => 'Purchase',
            4 => 'Purchase Return',
            5 => 'Expense',
            6 => 'Production',
            7 => 'Stock Adjustment',
            8 => 'Stock Adjustment RCV AMT',
            9 => 'Expense Payment',
            10 => 'Receive Payment',
            // 10 => 'Receive From Customer',
            11 => 'Purchase Payment',
            12 => 'Sale Return Payment',
            13 => 'Loan&Liabilities',
            14 => 'Loan&Advance',
            15 => 'Loan Liability Payment',
            16 => 'Loan&Advance Receive',
            17 => 'Receive Return Amt.',
            18 => 'Received From Customer',
            19 => 'Paid To Supplier',
            20 => 'Paid To Customer',
            21 => 'Received From Supplier',
            22 => 'Production',
            23 => 'Payroll Payment',
            24 => 'Loan&Liabilities-money-trans',
            25 => 'Loan&Advance-money-trans',
            26 => 'Contra',
            27 => 'Contra',
        ];
    }

    public function voucherType($voucher_type_id)
    {
        $data = [
            0 => ['name' => 'Opening balance', 'voucher_no' => 'sale_inv_id', 'id' => 'sale_id', 'amt' => 'debit/credit', 'pur' => 'sale_pur'],
            1 => ['name' => 'Sales', 'voucher_no' => 'sale_inv_id', 'id' => 'sale_id', 'amt' => 'credit', 'pur' => 'sale_pur'],
            2 => ['name' => 'Sale Return', 'voucher_no' => 'sale_return_inv', 'id' => 'sale_return_id', 'amt' => 'debit', 'pur' => 'sale_return_pur'],
            3 => ['name' => 'Purchase', 'voucher_no' => 'purchase_inv_id', 'id' => 'purchase_id', 'amt' => 'debit', 'pur' => 'purchase_pur'],
            4 => ['name' => 'Purchase Return', 'voucher_no' => 'pur_return_invoice', 'id' => 'purchase_return_id', 'amt' => 'credit', 'pur' => 'purchase_return_pur'],
            5 => ['name' => 'Expense', 'voucher_no' => 'exp_voucher_no', 'id' => 'expense_id', 'amt' => 'debit', 'pur' => 'ex_pur'],
            6 => ['name' => 'Production', 'voucher_no' => 'production_voucher', 'id' => 'production_id', 'amt' => 'debit', 'pur' => 'production_pur'],
            7 => ['name' => 'Stock Adjustment', 'voucher_no' => 'sa_voucher', 'id' => 'adjustment_id', 'amt' => 'credit', 'pur' => 'adjustment_pur'],
            8 => ['name' => 'Adjustment Recovered', 'voucher_no' => 'sar_amt_voucher', 'id' => 'stock_adjustment_recover_id', 'amt' => 'debit', 'pur' => 'sar_pur'],
            9 => ['name' => 'Expense Payment', 'voucher_no' => 'exp_payment_voucher', 'id' => 'expense_payment_id', 'amt' => 'credit', 'pur' => 'expense_payment_pur'],
            10 => ['name' => 'Receive Payment', 'voucher_no' => 'sale_payment_voucher', 'id' => 'sale_payment_id', 'amt' => 'debit', 'pur' => 'sale_payment_pur'],
            11 => ['name' => 'Purchase Payment', 'voucher_no' => 'pur_payment_voucher', 'id' => 'purchase_payment_id', 'amt' => 'credit', 'pur' => 'purchase_payment_pur'],
            12 => ['name' => 'Sale Return Payment', 'voucher_no' => 'sale_payment_voucher', 'id' => 'sale_payment_id', 'amt' => 'credit', 'pur' => 'sale_payment_pur'],
            13 => ['name' => 'Loan&Liabilities', 'voucher_no' => 'loan_voucher_no', 'id' => 'loan_id', 'amt' => 'credit', 'pur' => 'loan_pur'],
            14 => ['name' => 'Loan&Advance', 'voucher_no' => 'loan_voucher_no', 'id' => 'loan_id', 'amt' => 'debit', 'pur' => 'loan_pur'],
            15 => ['name' => 'Loan Liability Payment', 'voucher_no' => 'loan_payment_voucher', 'id' => 'loan_payment_id', 'amt' => 'credit', 'pur' => 'loan_pay_pur'],
            16 => ['name' => 'Loan&Advance Receive', 'voucher_no' => 'loan_payment_voucher', 'id' => 'loan_payment_id', 'amt' => 'debit', 'pur' => 'loan_pay_pur'],
            17 => ['name' => 'Receive Return Amt.', 'voucher_no' => 'pur_payment_voucher', 'id' => 'purchase_payment_id', 'amt' => 'debit', 'pur' => 'purchase_payment_pur'],
            18 => ['name' => 'Received From Customer', 'voucher_no' => 'customer_payment_voucher', 'id' => 'customer_payment_id', 'amt' => 'debit', 'pur' => 'customer_payment_pur'],
            19 => ['name' => 'Paid To Supplier', 'voucher_no' => 'supplier_payment_voucher', 'id' => 'supplier_payment_id', 'amt' => 'credit', 'pur' => 'supplier_payment_pur'],
            20 => ['name' => 'Paid To Customer', 'voucher_no' => 'customer_payment_voucher', 'id' => 'customer_payment_id', 'amt' => 'credit', 'pur' => 'customer_payment_pur'],
            21 => ['name' => 'Received From Supplier', 'voucher_no' => 'supplier_payment_voucher', 'id' => 'supplier_payment_id', 'amt' => 'debit', 'pur' => 'supplier_payment_pur'],
            22 => ['name' => 'Production', 'voucher_no' => 'production_voucher', 'id' => 'production_id', 'amt' => 'debit', 'pur' => 'production_pur'],
            23 => ['name' => 'Payroll Payment', 'voucher_no' => 'payroll_pay_voucher', 'id' => 'payroll_payment_id', 'amt' => 'credit', 'pur' => 'payroll_payment_pur'],
            24 => ['name' => 'Loan&Liabilities-money-trans', 'voucher_no' => 'loan_voucher_no', 'id' => 'loan_id', 'amt' => 'debit', 'pur' => 'loan_pur'],
            25 => ['name' => 'Loan&Advance-money-trans', 'voucher_no' => 'loan_voucher_no', 'id' => 'loan_id', 'amt' => 'credit', 'pur' => 'loan_pur'],
            26 => ['name' => 'Contra', 'voucher_no' => 'co_debit_voucher_no', 'id' => 'contra_debit_id', 'amt' => 'debit', 'pur' => 'co_debit_pur'],
            27 => ['name' => 'Contra', 'voucher_no' => 'co_credit_voucher_no', 'id' => 'contra_credit_id', 'amt' => 'credit', 'pur' => 'co_credit_pur'],
        ];

        return $data[$voucher_type_id];
    }

    public function addAccountLedger($voucher_type_id, $date, $account_id, $trans_id, $amount, $balance_type)
    {
        $voucherType = $this->voucherType($voucher_type_id);
        $add = new AccountLedger();
        $add->date = date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $add->account_id = $account_id;
        $add->voucher_type = $voucher_type_id;
        $add->{$voucherType['id']} = $trans_id;
        $add->{$voucherType['amt']} = $amount;
        $add->amount_type = $voucherType['amt'];
        $add->branch_id = auth()->user()->branch_id;
        $add->save();
        $add->running_balance = $this->adjustAccountBalance($balance_type, $account_id);
        $add->save();
    }

    public function updateAccountLedger($voucher_type_id, $date, $account_id, $trans_id, $amount, $balance_type)
    {
        $voucherType = $this->voucherType($voucher_type_id);
        $update = AccountLedger::where($voucherType['id'], $trans_id)->first();
        if ($update) {

            $previousAccountId = $update->account_id;

            $previousTime = date('H:i:s', strtotime($update->date));
            $update->date = date('Y-m-d H:i:s', strtotime($date . $previousTime));
            $update->account_id = $account_id;
            $update->{$voucherType['amt']} = $amount;
            $update->save();
            $update->running_balance = $this->adjustAccountBalance($balance_type, $account_id);
            $update->save();

            if ($previousAccountId != $account_id) {

                $this->adjustAccountBalance($balance_type, $previousAccountId);
            }
        } else {

            $this->addAccountLedger($voucher_type_id, $date, $account_id, $trans_id, $amount, $balance_type);
        }
    }

    public function accountBalanceType($balance_type)
    {
        $data = [
            1 => 'debit', 2 => 'debit', 3 => 'debit', 4 => 'credit', 5 => 'credit', 6 => 'debit', 7 => 'debit', 8 => 'debit', 9 => 'debit', 10 => 'debit', 11 => 'debit', 12 => 'credit', 13 => 'credit', 14 => 'debit', 15 => 'debit', 16 => 'debit', 17 => 'debit', 18 => 'credit', 19 => 'debit', 20 => 'debit', 21 => 'debit', 22 => 'credit', 23 => 'debit', 24 => 'credit', 25 => 'credit', 26 => 'credit',
        ];

        return $data[$balance_type];
    }
}
