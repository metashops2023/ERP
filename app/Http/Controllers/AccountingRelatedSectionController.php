<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;

use App\Models\CashFlow;
use App\Utils\AccountUtil;
use App\Utils\NetProfitLossAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingRelatedSectionController extends Controller
{
    protected $accountUtil;
    protected $netProfitLossAccount;

    public function __construct(AccountUtil $accountUtil, NetProfitLossAccount $netProfitLossAccount)
    {
        $this->accountUtil = $accountUtil;
        $this->netProfitLossAccount = $netProfitLossAccount;
        $this->middleware('auth:admin_and_user');
    }

    // balance sheet view
    public function balanceSheet()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('accounting.related_sections.balance_sheet', compact('branches'));
    }

    // Get balance sheet amounts **requested by ajax**
    public function balanceSheetAmounts(Request $request)
    {
        $totalCashInHand = '';
        $TotalBankBalance = '';
        $TotalCapital = '';
        $TotalInvestment = '';
        $fixedAssets = '';
        $loanCompanies = '';
        $singleProductStockValueValue = '';
        $variantProductStockValueValue = '';

        $suppliers = DB::table('suppliers')
            ->select(
                DB::raw('SUM(total_purchase_due) as total_due'),
                DB::raw('SUM(total_purchase_return_due) as total_return_due'),
            )->get();

        $customers = DB::table('customers')
            ->select(
                DB::raw('SUM(total_sale_due) as total_due'),
                DB::raw('SUM(total_sale_return_due) as total_return_due'),
            )->get();

        $totalCashInHandQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_cash'))
            ->where('accounts.account_type', 1)
            ->groupBy('accounts.account_type');

        $TotalBankBalanceQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_bank_balance'))
            ->where('accounts.account_type', 2)
            ->groupBy('accounts.account_type');

        $TotalCapitalQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_capital'))
            ->where('accounts.account_type', 26)
            ->groupBy('accounts.account_type');

        $TotalInvestmentQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_investment'))
            ->where('accounts.account_type', 16)->groupBy('accounts.account_type');

        $fixedAssetQuery = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(
                'accounts.name',
                'accounts.balance'
            )->where('accounts.account_type', 15)->orderBy('accounts.id', 'desc');

        $loanCompanyQuery = DB::table('loan_companies')
            ->select(
                DB::raw('SUM(pay_loan_due) as total_la_receivable'),
                DB::raw('SUM(get_loan_due) as total_ll_payable'),
            );

        $singleProductStockValueQuery = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->where('products.is_variant', 0)
            ->select(DB::raw('SUM(products.product_cost_with_tax * product_branches.product_quantity) as total'));

        $variantProductStockValueQuery = DB::table('product_branch_variants')
            ->leftJoin('product_branches', 'product_branch_variants.product_branch_id', 'product_branches.id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->select(DB::raw('SUM(product_variants.variant_cost_with_tax * product_branch_variants.variant_quantity) as total'));

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $totalCashInHandQuery->where('account_branches.branch_id', NULL);

                $TotalBankBalanceQuery->where('account_branches.branch_id', NULL);

                $TotalCapitalQuery->where('account_branches.branch_id', NULL);

                $TotalInvestmentQuery->where('account_branches.branch_id', NULL);

                $fixedAssetQuery->where('account_branches.branch_id', NULL);

                $loanCompanyQuery->where('loan_companies.branch_id', NULL);

                $singleProductStockValueQuery->where('product_branches.branch_id', NULL);

                $variantProductStockValueQuery->where('product_branches.branch_id', NULL);
            } else {

                $totalCashInHandQuery->where('account_branches.branch_id', $request->branch_id);

                $TotalBankBalanceQuery->where('account_branches.branch_id', $request->branch_id);

                $TotalCapitalQuery->where('account_branches.branch_id', $request->branch_id);

                $TotalInvestmentQuery->where('account_branches.branch_id', $request->branch_id);

                $fixedAssetQuery->where('account_branches.branch_id', $request->branch_id);

                $loanCompanyQuery->where('loan_companies.branch_id', $request->branch_id);

                $singleProductStockValueQuery->where('product_branches.branch_id', $request->branch_id);

                $variantProductStockValueQuery->where('product_branches.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $totalCashInHand = $totalCashInHandQuery->get();

            $TotalBankBalance = $TotalBankBalanceQuery->get();

            $TotalCapital = $TotalCapitalQuery->get();

            $TotalInvestment = $TotalInvestmentQuery->get();

            $fixedAssets = $fixedAssetQuery->get();

            $loanCompanies = $loanCompanyQuery->get();

            $singleProductStockValue = $singleProductStockValueQuery->get();

            $variantProductStockValue = $variantProductStockValueQuery->get();
        } else {

            $totalCashInHand = $totalCashInHandQuery->where('account_branches.branch_id', auth()->user()->branch_id)
            ->get();

            $TotalBankBalance = $TotalBankBalanceQuery->where('account_branches.branch_id', auth()->user()->branch_id)->get();

            $TotalCapital = $TotalCapitalQuery->where('account_branches.branch_id', auth()->user()->branch_id)->get();

            $TotalInvestment = $TotalInvestmentQuery->where('account_branches.branch_id', auth()->user()->branch_id)->get();

            $fixedAssets = $fixedAssetQuery->where('account_branches.branch_id', auth()->user()->branch_id)->get();

            $loanCompanies = $loanCompanyQuery->where('loan_companies.branch_id', auth()->user()->branch_id)->get();

            $singleProductStockValue = $singleProductStockValueQuery->where('product_branches.branch_id', auth()->user()->branch_id)->get();

            $variantProductStockValue = $variantProductStockValueQuery->where('product_branches.branch_id', auth()->user()->branch_id)->get();
        }

        // $totalCashInHand = $totalCashInHandQuery->get();

        // $TotalBankBalance = $TotalBankBalanceQuery->get();

        // $TotalCapital = $TotalCapitalQuery->get();

        // $TotalInvestment = $TotalInvestmentQuery->get();

        // $fixedAssets = $fixedAssetQuery->get();

        // $loanCompanies = $loanCompanyQuery->get();

        // $singleProductStockValue = $singleProductStockValueQuery->get();

        // $variantProductStockValue = $variantProductStockValueQuery->get();

        $currentStockValue = $singleProductStockValue->sum('total') + $variantProductStockValue->sum('total');

        $netProfitLossAccount = $this->netProfitLossAccount->netLossProfit($request);

        return view(
            'accounting.related_sections.ajax_view.balance_sheet_ajax_view',
            compact(
                'suppliers',
                'customers',
                'totalCashInHand',
                'TotalBankBalance',
                'TotalInvestment',
                'fixedAssets',
                'TotalCapital',
                'loanCompanies',
                'currentStockValue',
                'netProfitLossAccount',
            )
        );
    }

    // Trial balance view
    public function trialBalance()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('accounting.related_sections.trial_balance');
    }

    // Get balance sheet amounts **requested by ajax**
    public function trialBalanceAmounts()
    {
        $customers = DB::table('customers')
            ->select(
                DB::raw('SUM(total_sale_due) as balance'),
                DB::raw('SUM(total_sale_return_due) as return_balance')
            )->get();

        $suppliers = DB::table('suppliers')
            ->select(
                DB::raw('SUM(total_purchase_due) as balance'),
                DB::raw('SUM(total_purchase_return_due) as return_balance')
            )->get();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->select(
                'accounts.account_type',
                DB::raw('SUM(accounts.balance) as total_balance')
            )->groupBy('accounts.account_type')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get();

        $openingStock = DB::table('product_opening_stocks')
            ->select(DB::raw('SUM(quantity * unit_cost_inc_tax) as total_value'))
            ->where('product_opening_stocks.branch_id', auth()->user()->branch_id)->get();

        $accountUtil = $this->accountUtil;

        return view(
            'accounting.related_sections.ajax_view.trial_balance_ajax_view',
            compact(
                'customers',
                'suppliers',
                'accounts',
                'openingStock',
                'accountUtil'
            )
        );
    }

    // Cash flow view
    public function cashFow()
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view('accounting.related_sections.cash_flow', compact('branches'));
    }

    // All cash flows **requested by ajax**
    public function cashFlowAmounts(Request $request)
    {
        $customerReceivable = DB::table('customers')->select(DB::raw('SUM(total_sale_due) as total_due'))->get();
        $supplierPayable = DB::table('suppliers')->select(DB::raw('SUM(total_purchase_due) as total_due'))->get();

        $netProfitLossAccount = $this->netProfitLossAccount->netLossProfit($request);

        $fixedAssets = '';
        $currentAssets = '';
        $currentLiability = '';
        $loanAndAdvance = '';

        $fixedAssetsQ = DB::table('expenses')
            ->leftJoin('accounts', 'expenses.expense_account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_fixed_asset'))
            ->where('accounts.account_type', 15);

        $currentAssetsQ = DB::table('expenses')
            ->leftJoin('accounts', 'expenses.expense_account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_current_asset'))
            ->where('accounts.account_type', 9);

        $currentLiabilityQ = DB::table('loans')
            ->leftJoin('loan_companies', 'loans.loan_company_id', 'loan_companies.id')
            ->where('loans.type', 2)
            ->select(DB::raw('SUM(due) as current_liability'));

        $loanAndAdvanceQ = DB::table('loans')
            ->where('loans.type', 1)
            ->leftJoin('loan_companies', 'loans.loan_company_id', 'loan_companies.id')
            ->select(DB::raw('SUM(due) as current_loan_receivable'));

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $fixedAssetsQ->where('expenses.branch_id', NULL);
                $currentAssetsQ->where('expenses.branch_id', NULL);
                $currentLiabilityQ->where('loan_companies.branch_id', NULL);
                $loanAndAdvanceQ->where('loan_companies.branch_id', NULL);
            } else {

                $fixedAssetsQ->where('expenses.branch_id', $request->branch_id);
                $currentAssetsQ->where('expenses.branch_id', $request->branch_id);
                $currentLiabilityQ->where('loan_companies.branch_id', $request->branch_id);
                $loanAndAdvanceQ->where('loan_companies.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $fixedAssetsQ->whereBetween('expenses.report_date', $date_range);
            $currentAssetsQ->whereBetween('expenses.report_date', $date_range);

            $currentLiabilityQ->whereBetween('loans.report_date', $date_range);
            $loanAndAdvanceQ->whereBetween('loans.report_date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $fixedAssets = $fixedAssetsQ->get();
            $currentAssets = $currentAssetsQ->get();
            $currentLiability = $currentLiabilityQ->get();
            $loanAndAdvance = $loanAndAdvanceQ->get();
        } else {

            $fixedAssets = $fixedAssetsQ->where('expenses.branch_id', auth()->user()->branch_id)->get();
            $currentAssets = $currentAssetsQ->where('expenses.branch_id', auth()->user()->branch_id)->get();
            $currentLiability = $currentLiabilityQ->where('loan_companies.branch_id', auth()->user()->branch_id)->get();
            $loanAndAdvance = $loanAndAdvanceQ->where('loan_companies.branch_id', auth()->user()->branch_id)->get();
        }

        return view(
            'accounting.related_sections.ajax_view.cash_flow_ajax_view',
            compact(
                'customerReceivable',
                'supplierPayable',
                'fixedAssets',
                'currentAssets',
                'currentLiability',
                'loanAndAdvance',
                'netProfitLossAccount',
            )
        );
    }

    public function printCashflow(Request $request)
    {
        $branch_id = $request->branch_id;

        $customerReceivable = DB::table('customers')->select(DB::raw('SUM(total_sale_due) as total_due'))->get();
        $supplierPayable = DB::table('suppliers')->select(DB::raw('SUM(total_purchase_due) as total_due'))->get();

        $netProfitLossAccount = $this->netProfitLossAccount->netLossProfit($request);

        $fixedAssets = '';
        $currentAssets = '';
        $currentLiability = '';
        $loanAndAdvance = '';

        $fixedAssetsQ = DB::table('expenses')
            ->leftJoin('accounts', 'expenses.expense_account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_fixed_asset'))
            ->where('accounts.account_type', 15);

        $currentAssetsQ = DB::table('expenses')
            ->leftJoin('accounts', 'expenses.expense_account_id', 'accounts.id')
            ->select(DB::raw('SUM(accounts.balance) as total_current_asset'))
            ->where('accounts.account_type', 9);

        $currentLiabilityQ = DB::table('loans')
            ->leftJoin('loan_companies', 'loans.loan_company_id', 'loan_companies.id')
            ->where('loans.type', 2)
            ->select(DB::raw('SUM(due) as current_liability'));

        $loanAndAdvanceQ = DB::table('loans')
            ->where('loans.type', 1)
            ->leftJoin('loan_companies', 'loans.loan_company_id', 'loan_companies.id')
            ->select(DB::raw('SUM(due) as current_loan_receivable'));

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $fixedAssetsQ->where('expenses.branch_id', NULL);
                $currentAssetsQ->where('expenses.branch_id', NULL);
                $currentLiabilityQ->where('loan_companies.branch_id', NULL);
                $loanAndAdvanceQ->where('loan_companies.branch_id', NULL);
            } else {

                $fixedAssetsQ->where('expenses.branch_id', $request->branch_id);
                $currentAssetsQ->where('expenses.branch_id', $request->branch_id);
                $currentLiabilityQ->where('loan_companies.branch_id', $request->branch_id);
                $loanAndAdvanceQ->where('loan_companies.branch_id', $request->branch_id);
            }
        }

        $fromDate = '';
        $toDate = '';

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = isset($request->to_date) && $request->to_date
                ? date('Y-m-d', strtotime($request->to_date))
                : $from_date;

            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $fixedAssetsQ->whereBetween('expenses.report_date', $date_range);
            $currentAssetsQ->whereBetween('expenses.report_date', $date_range);

            $currentLiabilityQ->whereBetween('loans.report_date', $date_range);
            $loanAndAdvanceQ->whereBetween('loans.report_date', $date_range);

            $fromDate = $from_date;
            $toDate = $to_date;
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $fixedAssets = $fixedAssetsQ->get();
            $currentAssets = $currentAssetsQ->get();
            $currentLiability = $currentLiabilityQ->get();
            $loanAndAdvance = $loanAndAdvanceQ->get();
        } else {

            $fixedAssets = $fixedAssetsQ->where('expenses.branch_id', auth()->user()->branch_id)->get();
            $currentAssets = $currentAssetsQ->where('expenses.branch_id', auth()->user()->branch_id)->get();
            $currentLiability = $currentLiabilityQ->where('loan_companies.branch_id', auth()->user()->branch_id)->get();
            $loanAndAdvance = $loanAndAdvanceQ->where('loan_companies.branch_id', auth()->user()->branch_id)->get();
        }

        return view(
            'accounting.related_sections.ajax_view.print_cash_flow',
            compact(
                'customerReceivable',
                'supplierPayable',
                'fixedAssets',
                'currentAssets',
                'currentLiability',
                'loanAndAdvance',
                'netProfitLossAccount',
                'fromDate',
                'toDate',
                'branch_id'
            )
        );
    }

    public function profitLossAccount()
    {
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('accounting.related_sections.loss_profit_ac', compact('branches'));
    }

    public function profitLossAccountAmounts(Request $request)
    {
        $netProfitLossAccount = $this->netProfitLossAccount->netLossProfit($request);

        return view(
            'accounting.related_sections.ajax_view.profit_loss_ac_ajax_view',
            compact('netProfitLossAccount',)
        );
    }
}
