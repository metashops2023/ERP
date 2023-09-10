<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Utils\Util;
use App\Models\Bank;
use App\Models\Account;
use App\Models\CashFlow;
use App\Utils\Converter;
use App\Utils\AccountUtil;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Models\AccountBranch;
use App\Models\AccountLedger;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{
    protected $accountUtil;
    protected $util;
    protected $converter;
    protected $userActivityLogUtil;

    public function __construct(
        AccountUtil $accountUtil,
        Util $util,
        Converter $converter,
        UserActivityLogUtil $userActivityLogUtil
    ) {
        $this->accountUtil = $accountUtil;
        $this->util = $util;
        $this->converter = $converter;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Bank main page/index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $accounts = '';
            $query = DB::table('account_branches')
                ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
                ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
                ->leftJoin('branches', 'account_branches.branch_id', 'branches.id');

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('account_branches.branch_id', NULL);
                } else {

                    $query->where('account_branches.branch_id', $request->branch_id);
                }
            }

            if ($request->account_type) {

                $query = $query->where('accounts.account_type', $request->account_type);
            }

            $query->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.opening_balance',
                'accounts.balance',
                'accounts.account_type',
                'banks.name as b_name',
                'banks.branch_name as b_branch',
                'branches.name as branch_name',
                'branches.branch_code',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $accounts = $query->orderBy('accounts.account_type', 'asc');
            } else {

                $accounts = $query->where('account_branches.branch_id', auth()->user()->branch_id)
                    ->orderBy('accounts.account_type', 'asc');
            }

            return DataTables::of($accounts)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';

                        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  '.__("Action").' </button>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                        $html .= '<a id="edit" class="dropdown-item" href="' . route('accounting.accounts.edit', [$row->id]) . '" ><i class="far fa-edit text-primary"></i>  '.__("Edit").' </a>';
                        $html .= '<a class="dropdown-item" href="' . route('accounting.accounts.book', [$row->id]) . '"><i class="fas fa-book text-primary"></i>   '.__("Account Book").'</a>';
                        $html .= '<a class="dropdown-item" href="' . route('accounting.accounts.delete', [$row->id]) . '" id="delete"><i class="fas fa-trash text-primary"></i>  '.__("Delete").' </a>';


                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })

                ->editColumn('ac_number', fn ($row) => $row->account_number ? $row->account_number : 'Not Applicable')

                ->editColumn('bank', fn ($row) => $row->b_name ? $row->b_name . ' (' . $row->b_branch . ')' : 'Not Applicable')

                ->editColumn('account_type', fn ($row) => '<b>' . $this->util->accountType($row->account_type) . '</b>')

                ->editColumn('branch', fn ($row) => '<b>' . ($row->branch_name ? $row->branch_name . '/' . $row->branch_code : json_decode($generalSettings->business, true)['shop_name']) . '</b>')

                ->editColumn('opening_balance', fn ($row) => $this->converter->format_in_bdt($row->opening_balance))

                ->editColumn('balance', fn ($row) => $this->converter->format_in_bdt($row->balance))

                ->rawColumns(['action', 'ac_number', 'bank', 'account_type', 'branch', 'opening_balance', 'balance'])

                ->make(true);
        }

        $banks = DB::table('banks')->get();
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('accounting.accounts.index', compact('banks', 'branches'));
    }

    //Get account book
    public function accountBook(Request $request, $accountId)
    {
        if (auth()->user()->permission->accounting['ac_access'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        $accountUtil = $this->accountUtil;

        $account = Account::with(['bank'])->where('id', $accountId)->first();

        if ($request->ajax()) {

            $settings = DB::table('general_settings')->first();

            $ledgers = '';

            $query = DB::table('account_ledgers')->where('account_ledgers.account_id', $accountId)
                ->leftJoin('expenses', 'account_ledgers.expense_id', 'expenses.id')
                ->leftJoin('expense_payments', 'account_ledgers.expense_payment_id', 'expense_payments.id')
                ->leftJoin('sales', 'account_ledgers.sale_id', 'sales.id')
                ->leftJoin('sale_payments', 'account_ledgers.sale_payment_id', 'sale_payments.id')
                ->leftJoin('supplier_payments', 'account_ledgers.supplier_payment_id', 'supplier_payments.id')
                ->leftJoin('sale_returns', 'account_ledgers.sale_return_id', 'sale_returns.id')
                ->leftJoin('purchases', 'account_ledgers.purchase_id', 'purchases.id')
                ->leftJoin('purchase_payments', 'account_ledgers.purchase_payment_id', 'purchase_payments.id')
                ->leftJoin('customer_payments', 'account_ledgers.customer_payment_id', 'customer_payments.id')
                ->leftJoin('purchase_returns', 'account_ledgers.purchase_return_id', 'purchase_returns.id')
                ->leftJoin('stock_adjustments', 'account_ledgers.adjustment_id', 'stock_adjustments.id')
                ->leftJoin('stock_adjustment_recovers', 'account_ledgers.stock_adjustment_recover_id', 'stock_adjustment_recovers.id')
                // ->leftJoin('hrm_payrolls', 'account_ledgers.payroll_id', 'hrm_payrolls.id')
                ->leftJoin('hrm_payroll_payments', 'account_ledgers.payroll_payment_id', 'hrm_payroll_payments.id')
                ->leftJoin('productions', 'account_ledgers.production_id', 'productions.id')
                ->leftJoin('loans', 'account_ledgers.loan_id', 'loans.id')
                ->leftJoin('loan_payments', 'account_ledgers.loan_payment_id', 'loan_payments.id')
                ->leftJoin('contras as contra_debit', 'account_ledgers.contra_debit_id', 'contra_debit.id')
                ->leftJoin('contras as contra_credit', 'account_ledgers.contra_credit_id', 'contra_credit.id')
                ->leftJoin('accounts as sender_ac', 'contra_debit.sender_account_id', 'sender_ac.id')
                ->leftJoin('accounts as receiver_ac', 'contra_credit.receiver_account_id', 'receiver_ac.id');

            if ($request->transaction_type) {

                $query->where('account_ledgers.amount_type', $request->transaction_type); // Final
            }

            if (isset($request->voucher_type)) {

                $query->where('account_ledgers.voucher_type', $request->voucher_type); // Final
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('account_ledgers.date', $date_range); // Final
            }

            $ledgers = $query->select(
                'account_ledgers.date',
                'account_ledgers.voucher_type',
                'account_ledgers.debit',
                'account_ledgers.credit',
                'account_ledgers.running_balance',
                'expenses.invoice_id as exp_voucher_no',
                'expenses.note as ex_pur',
                'expense_payments.invoice_id as exp_payment_voucher',
                'expense_payments.note as expense_payment_pur',
                'sales.invoice_id as sale_inv_id',
                'sales.sale_note as sale_pur',
                'sale_payments.invoice_id as sale_payment_voucher',
                'sale_payments.note as sale_payment_pur',
                'supplier_payments.voucher_no as supplier_payment_voucher',
                'supplier_payments.note as supplier_payment_pur',
                'sale_returns.invoice_id as sale_return_inv',
                'sale_returns.date as sale_return_pur',
                'purchases.invoice_id as purchase_inv_id',
                'purchases.purchase_note as purchase_pur',
                'purchase_payments.invoice_id as pur_payment_voucher',
                'purchase_payments.note as purchase_payment_pur',
                'customer_payments.voucher_no as customer_payment_voucher',
                'customer_payments.note as customer_payment_pur',
                'purchase_returns.invoice_id as pur_return_invoice',
                'purchase_returns.date as purchase_return_pur',
                'stock_adjustments.invoice_id as sa_voucher',
                'stock_adjustments.reason as adjustment_pur',
                'stock_adjustment_recovers.voucher_no as sar_amt_voucher',
                'stock_adjustment_recovers.note as sar_pur',
                'hrm_payroll_payments.reference_no as payroll_pay_voucher',
                'hrm_payroll_payments.note as payroll_payment_pur',
                'productions.reference_no as production_voucher',
                'loans.reference_no as loan_voucher_no',
                'loans.loan_reason as loan_pur',
                'loan_payments.voucher_no as loan_payment_voucher',
                'loan_payments.date as loan_pay_pur',
                'contra_debit.voucher_no as co_debit_voucher_no',
                'contra_debit.remarks as co_debit_pur',
                'contra_credit.voucher_no as co_credit_voucher_no',
                'contra_credit.remarks as co_credit_pur',
                'receiver_ac.name as receiver_acn',
                'sender_ac.name as sender_acn',
            )->orderBy('account_ledgers.date', 'asc');

            $ledgers = $ledgers->get();
            $balanceType = $accountUtil->accountBalanceType($account->account_type);
            $tempRunning = 0;

            foreach ($ledgers as $ledger) {

                if ($balanceType == 'debit') {

                    $ledger->running_balance =  $tempRunning + ($ledger->debit - $ledger->credit);
                    $tempRunning = $ledger->running_balance;
                } elseif ($balanceType == 'credit') {

                    $ledger->running_balance =  $tempRunning + ($ledger->credit - $ledger->debit);
                    $tempRunning = $ledger->running_balance;
                }
            }

            return DataTables::of($ledgers)
                ->editColumn('date', function ($row) use ($settings) {

                    $dateFormat = json_decode($settings->business, true)['date_format'];
                    $__date_format = str_replace('-', '/', $dateFormat);
                    return date($__date_format, strtotime($row->date));
                })
                ->editColumn('particulars', function ($row) use ($accountUtil) {

                    $type = $accountUtil->voucherType($row->voucher_type);
                    $des = $row->{$type['pur']} ? '/' . $row->{$type['pur']} : '';
                    $receiver_ac = $row->receiver_acn ? '/To:<b>' . $row->receiver_acn . '</b>' : '';
                    $sender_ac = $row->sender_acn ? '/From:<b>' . $row->sender_acn . '</b>' : '';
                    return '<b>' . $type['name'] . '</b>' . $receiver_ac . $sender_ac . $des;
                    //return '<b>' . $type['name'].'</b>';
                })
                ->editColumn('voucher_no',  function ($row) use ($accountUtil) {

                    $type = $accountUtil->voucherType($row->voucher_type);
                    return $row->{$type['voucher_no']};
                })
                ->editColumn('debit', fn ($row) => '<span class="debit" data-value="' . $row->debit . '">' . $this->converter->format_in_bdt($row->debit) . '</span>')
                ->editColumn('credit', fn ($row) => '<span class="credit" data-value="' . $row->credit . '">' . $this->converter->format_in_bdt($row->credit) . '</span>')
                ->editColumn('running_balance', fn ($row) => '<span class="running_balance">' . $this->converter->format_in_bdt($row->running_balance) . '</span>')
                ->rawColumns(['date', 'particulars', 'voucher_no', 'debit', 'credit', 'running_balance'])
                ->make(true);
        }

        return view('accounting.accounts.account_book', compact('account', 'accountUtil'));
    }

    // Store bank
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'account_type' => 'required',
        ]);

        if ($request->account_type == 2) {

            $this->validate($request, [
                'bank_id' => 'required',
                'account_number' => 'required',
                "business_location"    => "required|array",
                "business_location.*"  => "required",
            ]);
        }

        $openingBalance = $request->opening_balance ? $request->opening_balance : 0;

        $addAccountGetId = Account::insertGetId([
            'name' => $request->name,
            'account_number' => $request->account_type == 2 ? $request->account_number : null,
            'bank_id' => $request->account_type == 2 ? $request->bank_id : null,
            'account_type' => $request->account_type,
            'opening_balance' => $openingBalance,
            'balance' => $openingBalance,
            $this->accountUtil->accountBalanceType($request->account_type) => $openingBalance,
            'remark' => $request->remark,
            'admin_id' => auth()->user()->id,
        ]);

        if ($request->account_type == 2) {

            foreach($request->business_location as $branch_id) {

                AccountBranch::insert(
                    [
                        'branch_id' => $branch_id != 'NULL' ? $branch_id : NULL,
                        'account_id' => $addAccountGetId,
                    ]
                );
            }
        } else {

            AccountBranch::insert(
                [
                    'branch_id' => auth()->user()->branch_id,
                    'account_id' => $addAccountGetId,
                ]
            );
        }

        // Add Opening Stock Ledger
        $accountLedger = new AccountLedger();
        $accountLedger->account_id = $addAccountGetId;
        $accountLedger->voucher_type = 0;
        $accountLedger->date = date('Y-m-d H:i:s');
        $accountLedger->{$this->accountUtil->accountBalanceType($request->account_type)} = $openingBalance;
        $accountLedger->amount_type = $this->accountUtil->accountBalanceType($request->account_type);
        $accountLedger->running_balance = $openingBalance;
        $accountLedger->save();

        $account = DB::table('accounts')->where('id', $addAccountGetId)
            ->select('name', 'account_number', 'opening_balance', 'balance')
            ->first();

        $this->userActivityLogUtil->addLog(
            action: 1,
            subject_type: 17,
            data_obj: $account
        );


            return response()->json(__('Account updated successfully'));


    }

    public function edit($id)
    {
        $account = Account::with('accountBranches')->where('id', $id)->first();
        $isExistsHeadOffice = DB::table('account_branches')->where('account_id', $id)->where('branch_id', NULL)->first();
        $banks = DB::table('banks')->get();
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('accounting.accounts.ajax_view.edit_account', compact('account', 'isExistsHeadOffice', 'banks', 'branches'));
    }

    // Update bank
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        if ($request->account_type == 2) {

            $this->validate($request, [
                'bank_id' => 'required',
                'account_number' => 'required',
                "business_location"    => "required|array",
                "business_location.*"  => "required",
            ]);
        }

        $updateAccount = Account::with('accountBranches')->where('id', $id)->first();

        // update account branches
        if ($updateAccount->account_type == 2) {

            foreach ($updateAccount->accountBranches as $accountBranch) {

                $accountBranch->is_delete_in_update = 1;
                $accountBranch->save();
            }
        }

        $openingBalance = $request->opening_balance ? $request->opening_balance : 0;

        $updateAccount->update([
            'name' => $request->name,
            'account_number' => $request->account_type == 2 ? $request->account_number : null,
            'bank_id' => $request->account_type == 2 ? $request->bank_id : null,
            'account_type' => $request->account_type,
            'opening_balance' => $openingBalance,
            'remark' => $request->remark,
        ]);

        if ($request->account_type == 2) {

            foreach ($request->business_location as $branch) {

                $branch_id = $branch != 'NULL' ? $branch : NULL;
                $accountBranch = AccountBranch::where('account_id', $updateAccount->id)->where('branch_id', $branch_id)->first();

                if ($accountBranch) {

                    $accountBranch->is_delete_in_update = 0;
                    $accountBranch->save();
                } else {

                    $addAccountBranch = AccountBranch::insert(
                        [
                            'branch_id' => $branch_id,
                            'account_id' => $updateAccount->id,
                        ]
                    );
                }
            }
        }

        // Delete unused account branch row
        $accountBranches = AccountBranch::where('account_id', $updateAccount->id)->where('is_delete_in_update', 1)->get();

        foreach ($accountBranches as $accountBranch) {

            $accountBranch->delete();
        }

        // Update Opening Balance Ledger
        $updateAccountLedger = AccountLedger::where('account_id', $updateAccount->id)->where('voucher_type', 0)->first();

        if ($updateAccountLedger) {

            $updateAccountLedger->{$this->accountUtil->accountBalanceType($request->account_type)} = $openingBalance;
            $updateAccountLedger->amount_type = $this->accountUtil->accountBalanceType($request->account_type);
            // $updateAccountLedger->save();

            // $runningBalance = $this->accountUtil->adjustAccountBalance(
            //     balanceType: $this->accountUtil->accountBalanceType($request->account_type),
            //     account_id: $updateAccount->id,
            // );

            // $updateAccountLedger->running_balance = $runningBalance;
            // $updateAccountLedger->save();

            $updateAccountLedger->running_balance = $openingBalance;
            $updateAccountLedger->save();
        } else {

            // Add Opening Ledger
            $accountLedger = new AccountLedger();
            $accountLedger->account_id = $updateAccount->id;
            $accountLedger->voucher_type = 0;
            $accountLedger->date = date('Y-m-d H:i:s');
            $accountLedger->{$this->accountUtil->accountBalanceType($request->account_type)} = $openingBalance;
            $accountLedger->amount_type = $this->accountUtil->accountBalanceType($request->account_type);
            $accountLedger->running_balance = $openingBalance;
            $accountLedger->save();
        }

        $runningBalance = $this->accountUtil->adjustAccountBalance(
            balanceType: $this->accountUtil->accountBalanceType($request->account_type),
            account_id: $updateAccount->id,
        );

        $account = DB::table('accounts')->where('id', $id)
            ->select('name', 'account_number', 'opening_balance', 'balance')
            ->first();

        $this->userActivityLogUtil->addLog(
            action: 2,
            subject_type: 17,
            data_obj: $account
        );

        return response()->json(__('Account created successfully'));

    }

    public function delete(Request $request, $accountId)
    {
        $deleteAccount = Account::with('accountLedgers')->where('id', $accountId)->first();

        if (count($deleteAccount->accountLedgers) > 1) {

            return response()->json('Account can not be deleted. One or more ledgers is belonging in this account.');
        }

        if (!is_null($deleteAccount)) {

            $this->userActivityLogUtil->addLog(
                action: 3,
                subject_type: 17,
                data_obj: $deleteAccount
            );

            $deleteAccount->delete();
        }

        return response()->json(__('Account deleted successfully'));

    }

    public function ledgerPrint(Request $request, $accountId)
    {
        $accountUtil = $this->accountUtil;

        $ledgers = '';

        $query = DB::table('account_ledgers')->where('account_ledgers.account_id', $accountId)
            ->leftJoin('expenses', 'account_ledgers.expense_id', 'expenses.id')
            ->leftJoin('expense_payments', 'account_ledgers.expense_payment_id', 'expense_payments.id')
            ->leftJoin('sales', 'account_ledgers.sale_id', 'sales.id')
            ->leftJoin('sale_payments', 'account_ledgers.sale_payment_id', 'sale_payments.id')
            ->leftJoin('supplier_payments', 'account_ledgers.supplier_payment_id', 'supplier_payments.id')
            ->leftJoin('sale_returns', 'account_ledgers.sale_return_id', 'sale_returns.id')
            ->leftJoin('purchases', 'account_ledgers.purchase_id', 'purchases.id')
            ->leftJoin('purchase_payments', 'account_ledgers.purchase_payment_id', 'purchase_payments.id')
            ->leftJoin('customer_payments', 'account_ledgers.customer_payment_id', 'customer_payments.id')
            ->leftJoin('purchase_returns', 'account_ledgers.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('stock_adjustments', 'account_ledgers.adjustment_id', 'stock_adjustments.id')
            ->leftJoin('stock_adjustment_recovers', 'account_ledgers.stock_adjustment_recover_id', 'stock_adjustment_recovers.id')
            // ->leftJoin('hrm_payrolls', 'account_ledgers.payroll_id', 'hrm_payrolls.id')
            ->leftJoin('hrm_payroll_payments', 'account_ledgers.payroll_payment_id', 'hrm_payroll_payments.id')
            ->leftJoin('productions', 'account_ledgers.production_id', 'productions.id')
            ->leftJoin('loans', 'account_ledgers.loan_id', 'loans.id')
            ->leftJoin('loan_payments', 'account_ledgers.loan_payment_id', 'loan_payments.id')
            ->leftJoin('contras as contra_debit', 'account_ledgers.contra_debit_id', 'contra_debit.id')
            ->leftJoin('contras as contra_credit', 'account_ledgers.contra_credit_id', 'contra_credit.id')
            ->leftJoin('accounts as sender_ac', 'contra_debit.sender_account_id', 'sender_ac.id')
            ->leftJoin('accounts as receiver_ac', 'contra_credit.receiver_account_id', 'receiver_ac.id');

        if ($request->transaction_type) {

            $query->where('account_ledgers.amount_type', $request->transaction_type); // Final
        }

        if (isset($request->voucher_type)) {

            $query->where('account_ledgers.voucher_type', $request->voucher_type); // Final
        }

        $fromDate = '';
        $toDate = '';

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('account_ledgers.date', $date_range); // Final

            $fromDate = $from_date;
            $toDate = $to_date;
        }

        $ledgers = $query->select(
            'account_ledgers.date',
            'account_ledgers.voucher_type',
            'account_ledgers.debit',
            'account_ledgers.credit',
            'account_ledgers.running_balance',
            'expenses.invoice_id as exp_voucher_no',
            'expenses.note as ex_pur',
            'expense_payments.invoice_id as exp_payment_voucher',
            'expense_payments.note as expense_payment_pur',
            'sales.invoice_id as sale_inv_id',
            'sales.sale_note as sale_pur',
            'sale_payments.invoice_id as sale_payment_voucher',
            'sale_payments.note as sale_payment_pur',
            'supplier_payments.voucher_no as supplier_payment_voucher',
            'supplier_payments.note as supplier_payment_pur',
            'sale_returns.invoice_id as sale_return_inv',
            'sale_returns.date as sale_return_pur',
            'purchases.invoice_id as purchase_inv_id',
            'purchases.purchase_note as purchase_pur',
            'purchase_payments.invoice_id as pur_payment_voucher',
            'purchase_payments.note as purchase_payment_pur',
            'customer_payments.voucher_no as customer_payment_voucher',
            'customer_payments.note as customer_payment_pur',
            'purchase_returns.invoice_id as pur_return_invoice',
            'purchase_returns.date as purchase_return_pur',
            'stock_adjustments.invoice_id as sa_voucher',
            'stock_adjustments.reason as adjustment_pur',
            'stock_adjustment_recovers.voucher_no as sar_amt_voucher',
            'stock_adjustment_recovers.note as sar_pur',
            'hrm_payroll_payments.reference_no as payroll_pay_voucher',
            'hrm_payroll_payments.note as payroll_payment_pur',
            'productions.reference_no as production_voucher',
            'loans.reference_no as loan_voucher_no',
            'loans.loan_reason as loan_pur',
            'loan_payments.voucher_no as loan_payment_voucher',
            'loan_payments.date as loan_pay_pur',
            'contra_debit.voucher_no as co_debit_voucher_no',
            'contra_debit.remarks as co_debit_pur',
            'contra_credit.voucher_no as co_credit_voucher_no',
            'contra_credit.remarks as co_credit_pur',
            'receiver_ac.name as receiver_acn',
            'sender_ac.name as sender_acn',
        )->orderBy('account_ledgers.date', 'asc')->get();

        $account = DB::table('accounts')
            ->where('accounts.id', $accountId)
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->select('accounts.id', 'accounts.name', 'account_type', 'accounts.account_number', 'accounts.balance', 'banks.name as bank_name')->first();

        return view('accounting.accounts.ajax_view.account_ledger_print', compact('account', 'ledgers', 'fromDate', 'toDate', 'accountUtil'));
    }
}
