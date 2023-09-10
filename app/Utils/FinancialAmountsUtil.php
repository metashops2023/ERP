<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Utils\NetProfitLossAccount;

class FinancialAmountsUtil
{
    protected $netProfitLossAccount;

    public function __construct(
        NetProfitLossAccount $netProfitLossAccount,
    ) {

        $this->netProfitLossAccount = $netProfitLossAccount;
    }

    public function allFinancialAmounts($request = NULL): array
    {
        $cashAndBankBalance = $this->cashAndBankBalance($request);
        $loanAmounts = $this->loanAmounts($request);
        // $salesSaleReturnAmount = $this->salesSaleReturnAmount($request);
        // $purchaseAndPurchaseReturnAmount = $this->purchaseAndPurchaseReturnAmount($request);
        // $expensesAmounts = $this->expensesAmounts($request);

        $netProfitLossAccountAmounts = $this->netProfitLossAccount->netLossProfit($request);

        $anotherAmounts = [];

        $anotherAmounts['fixed_asset_balance'] = $this->fixedAssetBalance($request);
        $anotherAmounts['cash_in_hand'] = $cashAndBankBalance['cash_in_hand_balance'];
        $anotherAmounts['bank_account'] = $cashAndBankBalance['bank_account_balance'];

        $dailyProfit = $netProfitLossAccountAmounts['total_sale']
            + $netProfitLossAccountAmounts['total_adjusted_recovered']
            - $netProfitLossAccountAmounts['total_unit_cost']
            - $netProfitLossAccountAmounts['total_direct_expense']
            - $netProfitLossAccountAmounts['total_indirect_expense']
            - $netProfitLossAccountAmounts['total_adjusted']
            - $netProfitLossAccountAmounts['total_sale_order_tax']
            - $netProfitLossAccountAmounts['total_sale_pro_tax']
            - $netProfitLossAccountAmounts['total_sale_return']
            - $netProfitLossAccountAmounts['total_transfer_cost'];

        $anotherAmounts['daily_profit'] = $dailyProfit;
        $anotherAmounts['total_loan_and_advance'] = $loanAmounts['total_loan_and_advance'];
        $anotherAmounts['total_loan_and_advance_due'] = $loanAmounts['total_loan_and_advance_due'];
        $anotherAmounts['total_loan_and_advance_received'] = $loanAmounts['total_loan_and_advance_received'];
        $anotherAmounts['total_loan_and_liability'] = $loanAmounts['total_loan_and_liability'];
        $anotherAmounts['total_loan_and_liability_due'] = $loanAmounts['total_loan_and_liability_due'];
        $anotherAmounts['total_loan_and_liability_paid'] = $loanAmounts['total_loan_and_liability_paid'];

        return array_merge($netProfitLossAccountAmounts, $anotherAmounts);
    }

    private function cashAndBankBalance($request)
    {
        $expenseLoan = '';
        $expenseLoanQ = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->where('account_ledgers.loan_id', '!=', NULL)
            // ->where('debit', '!=', NULL)
            ->leftJoin('loans', 'account_ledgers.loan_id', 'loans.id')
            ->where('loans.loan_by', 'Expense');

        $cashInHandAmounts = '';
        $cashInHandAmountsQ = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $cashInHandAmountsQ->where('account_branches.branch_id', NULL);
                $expenseLoanQ->where('account_branches.branch_id', NULL);
            } else {

                $cashInHandAmountsQ->where('account_branches.branch_id', $request->branch_id);
                $expenseLoanQ->where('account_branches.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $cashInHandAmountsQ->whereBetween('account_ledgers.date', $date_range);
            $expenseLoanQ->whereBetween('account_ledgers.date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $cashInHandAmounts = $cashInHandAmountsQ->select(
                'accounts.account_type',
                DB::raw('SUM(account_ledgers.debit) as total_debit'),
                DB::raw('SUM(account_ledgers.credit) as total_credit')
            )->groupBy('accounts.account_type')->get();

            $expenseLoan = $expenseLoanQ->where('loans.loan_by', 'Expense')
                ->select(DB::raw('sum(account_ledgers.credit) as t_credit'))
                ->groupBy('loans.loan_by')
                ->get();
        } else {

            $cashInHandAmounts = $cashInHandAmountsQ
                ->where('account_branches.branch_id', auth()->user()->branch_id)
                ->select(
                    'accounts.account_type',
                    DB::raw('SUM(account_ledgers.debit) as total_debit'),
                    DB::raw('SUM(account_ledgers.credit) as total_credit')
                )->groupBy('accounts.account_type')->get();

            $expenseLoan = $expenseLoanQ
                ->where('account_branches.branch_id', auth()->user()->branch_id)
                ->where('loans.loan_by', 'Expense')->select(DB::raw('sum(account_ledgers.credit) as t_credit'))
                ->groupBy('loans.loan_by')
                ->get();
        }

        $totalExpenseLoan = $expenseLoan->sum('t_credit') ? $expenseLoan->sum('t_credit') : 0;

        $balance = ['cash_in_hand_balance' => 0, 'bank_account_balance' => 0];

        foreach ($cashInHandAmounts as $cashInHandAmount) {

            if ($cashInHandAmount->account_type == 1) {

                $balance['cash_in_hand_balance'] = $cashInHandAmount->total_debit - $cashInHandAmount->total_credit;
            } else {

                $balance['bank_account_balance'] = $cashInHandAmount->total_debit - ($cashInHandAmount->total_credit - $totalExpenseLoan);
            }
        }

        return $balance;
    }

    public function fixedAssetBalance($request)
    {
        $fixedAssetAmounts = '';
        $fixedAssetAmountsQ = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('accounts.account_type', 15)
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $fixedAssetAmountsQ->where('account_branches.branch_id', NULL);
            } else {

                $fixedAssetAmountsQ->where('account_branches.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $fixedAssetAmountsQ->whereBetween('account_ledgers.date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $fixedAssetAmounts = $fixedAssetAmountsQ->groupBy('accounts.account_type');
        } else {

            $fixedAssetAmounts = $fixedAssetAmountsQ
                ->where('account_branches.branch_id', auth()->user()->branch_id)
                ->groupBy('accounts.account_type');
        }

        $fixedAssetDebitCredit = $fixedAssetAmounts->select(
            DB::raw('SUM(account_ledgers.debit) as total_debit'),
            DB::raw('SUM(account_ledgers.credit) as total_credit')
        )->get();

        return $fixedAssetDebitCredit->sum('total_debit') - $fixedAssetDebitCredit->sum('total_credit');
    }

    public function loanAmounts($request)
    {
        //DB::raw('sum(case when due > 0 then due end) as total_due')
        $loans = '';
        $loanQ = DB::table('loans');

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $loanQ->where('loans.branch_id', NULL);
            } else {

                $loanQ->where('loans.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $loanQ->whereBetween('loans.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $loanQ;
        } else {

            $loanQ->where('loans.branch_id', auth()->user()->branch_id)
                ->groupBy('loans.id');
        }

        $loans = $loanQ->select(
            DB::raw('sum(case when loans.type = 1 then loan_amount end) as total_loan_and_advance'),
            DB::raw('sum(case when loans.type = 1 then due end) as total_loan_and_advance_due'),
            DB::raw('sum(case when loans.type = 1 then total_receive end) as total_loan_and_advance_received'),
            DB::raw('sum(case when loans.type = 2 then loan_amount end) as total_loan_and_liability'),
            DB::raw('sum(case when loans.type = 2 then due end) as total_loan_and_liability_due'),
            DB::raw('sum(case when loans.type = 2 then total_paid end) as total_loan_and_liability_paid'),
        )->get();

        return [
            'total_loan_and_advance' => $loans->sum('total_loan_and_advance') ? $loans->sum('total_loan_and_advance') : 0,
            'total_loan_and_advance_due' => $loans->sum('total_loan_and_advance_due') ? $loans->sum('total_loan_and_advance_due') : 0,
            'total_loan_and_advance_received' => $loans->sum('total_loan_and_advance_received') ? $loans->sum('total_loan_and_advance_received') : 0,
            'total_loan_and_liability' => $loans->sum('total_loan_and_liability') ? $loans->sum('total_loan_and_liability') : 0,
            'total_loan_and_liability_due' => $loans->sum('total_loan_and_liability_due') ? $loans->sum('total_loan_and_liability_due') : 0,
            'total_loan_and_liability_paid' => $loans->sum('total_loan_and_liability_paid') ? $loans->sum('total_loan_and_liability_paid') : 0,
        ];
    }
}
