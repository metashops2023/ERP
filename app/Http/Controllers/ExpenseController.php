<?php

namespace App\Http\Controllers;

use App\Utils\Util;
use App\Models\Expense;
use App\Models\CashFlow;
use App\Utils\AccountUtil;
use App\Utils\ExpenseUtil;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ExpensePayment;
use App\Models\ExpenseCategory;
use App\Models\ExpenseDescription;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Utils\InvoiceVoucherRefIdUtil;

class ExpenseController extends Controller
{
    protected $expenseUtil;
    protected $accountUtil;
    protected $invoiceVoucherRefIdUtil;
    protected $userActivityLogUtil;
    protected $util;
    public function __construct(
        ExpenseUtil $expenseUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        UserActivityLogUtil $userActivityLogUtil,
        Util $util
    ) {
        $this->expenseUtil = $expenseUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->util = $util;
        $this->middleware('auth:admin_and_user');
    }

    // Expense index view
    public function index(Request $request)
    {
        if (auth()->user()->permission->expense['view_expense'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->expenseUtil->expenseListTable($request);
        }

        $ex_cates = DB::table('expense_categories')->get();

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('expenses.index', compact('branches', 'ex_cates'));
    }

    public function categoryWiseExpense(Request $request)
    {
        if (auth()->user()->permission->expense['category_wise_expense'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->expenseUtil->categoryWiseExpenseListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('expenses.category_wise_expense_list', compact('branches'));
    }

    // Create expense view
    public function create()
    {
        if (auth()->user()->permission->expense['add_expense'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        $users = DB::table('admin_and_users')->where('branch_id', auth()->user()->branch_id)
            ->select('id', 'prefix', 'name', 'last_name')->get();

        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $expenseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->whereIn('accounts.account_type', [7, 8, 9, 10, 15])
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'account_type']);

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        return view('expenses.create', compact('expenseAccounts', 'accounts', 'methods', 'users', 'taxes'));
    }

    // Store Expense
    public function store(Request $request)
    {
        if (auth()->user()->permission->expense['add_expense'] == '0') {

            return response()->json('Access Denied');
        }

        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['expenses'];
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['expense_payment'];

        $this->validate($request, [
            'date' => 'required',
            'ex_account_id' => 'required',
            'account_id' => 'required',
            'total_amount' => 'required',
            'paying_amount' => 'required',
        ]);

        // Add expense
        $addExpense = new Expense();
        $addExpense->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('expenses'), 5, "0", STR_PAD_LEFT);
        $addExpense->expense_account_id = $request->ex_account_id;
        $addExpense->branch_id = auth()->user()->branch_id;
        $addExpense->tax_percent = $request->tax ? $request->tax : 0;
        $addExpense->total_amount = $request->total_amount;
        $addExpense->net_total_amount = $request->net_total_amount;
        $addExpense->paid = $request->paying_amount;
        $addExpense->due = $request->total_due;
        $addExpense->date = $request->date;
        $addExpense->report_date = date('Y-m-d', strtotime($request->date));
        $addExpense->month = date('F');
        $addExpense->year = date('Y');
        $addExpense->admin_id = $request->admin_id;
        $category_ids = '';

        foreach ($request->category_ids as $category_id) {

            $category_ids .= $category_id . ', ';
        }

        $addExpense->category_ids = $category_ids;

        if ($request->hasFile('attachment')) {

            $expenseAttachment = $request->file('attachment');
            $expenseAttachmentName = uniqid() . '-' . '.' . $expenseAttachment->getClientOriginalExtension();
            $expenseAttachment->move(public_path('uploads/expense_attachment/'), $expenseAttachmentName);
            $addExpense->attachment = $expenseAttachmentName;
        }

        $addExpense->save();

        $this->userActivityLogUtil->addLog(
            action: 1,
            subject_type: 15,
            data_obj: $addExpense
        );

        $index = 0;
        foreach ($request->category_ids as $category_id) {

            $addExDescription = new ExpenseDescription();
            $addExDescription->expense_id = $addExpense->id;
            $addExDescription->expense_category_id = $category_id;
            $addExDescription->amount = $request->amounts[$index];
            $addExDescription->save();
            $index++;
        }

        // Add expense account Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 5,
            date: $request->date,
            account_id: $request->ex_account_id,
            trans_id: $addExpense->id,
            amount: $request->net_total_amount,
            balance_type: 'debit'
        );

        if ($request->paying_amount > 0) {

            $addPaymentGetId = $this->expenseUtil->addPaymentGetId(
                voucher_prefix: $paymentInvoicePrefix,
                expense_id: $addExpense->id,
                request: $request
            );

            // Add bank account Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 9,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $addPaymentGetId,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );
        }

        $expense = Expense::with(['expense_descriptions', 'expense_descriptions.category', 'admin'])
            ->where('id', $addExpense->id)->first();

        return view('expenses.ajax_view.expense_print', compact('expense'));
    }

    //Delete Expense
    public function delete(Request $request, $expenseId)
    {
        if (auth()->user()->permission->expense['delete_expense'] == '0') {

            return response()->json('Access Denied');
        }

        $deleteExpense = Expense::with('expense_payments')->where('id', $expenseId)->first();

        if ($deleteExpense->transfer_branch_to_branch_id) {

            return response()->json(
                'Expense can not be deleted. This expense is belonging a business location to business location transfer.'
            );
        }

        $this->userActivityLogUtil->addLog(action: 3, subject_type: 15, data_obj: $deleteExpense);

        $this->expenseUtil->expenseDelete($deleteExpense);


        return response()->json(__('Successfully expense is deleted'));

    }

    // Edit view
    public function edit($expenseId)
    {
        if (auth()->user()->permission->expense['edit_expense'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $expense = Expense::with('expense_descriptions')->where('id', $expenseId)->first();

        if ($expense->transfer_branch_to_branch_id) {

            session()->flash('errorMsg', 'Can not be edited. Expense is created by Business Location to Business Location Transfer.');

            return redirect()->back();
        }

        $categories = DB::table('expense_categories')->get();

        $taxes = DB::table('taxes')->get();

        $users = DB::table('admin_and_users')
            ->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'prefix', 'name', 'last_name']);

        $expenseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->whereIn('account_type', [7, 8, 9, 10, 15])
            ->get(['accounts.id', 'accounts.name', 'account_type']);

        return view('expenses.edit', compact('expense', 'categories', 'users', 'taxes', 'expenseAccounts'));
    }

    // Update expense
    public function update(Request $request, $expenseId)
    {
        if (auth()->user()->permission->expense['edit_expense'] == '0') {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'date' => 'required',
            'ex_account_id' => 'required',
            'total_amount' => 'required',
        ]);

        // Add expense
        $updateExpense = Expense::where('id', $expenseId)->first();
        $updateExpense->expense_account_id = $request->ex_account_id;
        $updateExpense->note = $request->expense_note;
        $updateExpense->tax_percent = $request->tax ? $request->tax : 0;
        $updateExpense->total_amount = $request->total_amount;
        $updateExpense->net_total_amount = $request->net_total_amount;
        $updateExpense->date = $request->date;
        $updateExpense->report_date = date('Y-m-d', strtotime($request->date));
        $updateExpense->month = date('F');
        $updateExpense->year = date('Y');
        $updateExpense->admin_id = $request->admin_id;

        if ($request->hasFile('attachment')) {

            if ($updateExpense->attachment != null) {

                if (file_exists(public_path('uploads/expense_attachment/' . $updateExpense->attachment))) {

                    unlink(public_path('uploads/expense_attachment/' . $updateExpense->attachment));
                }
            }

            $expenseAttachment = $request->file('attachment');
            $expenseAttachmentName = uniqid() . '-' . '.' . $expenseAttachment->getClientOriginalExtension();
            $expenseAttachment->move(public_path('uploads/expense_attachment/'), $expenseAttachmentName);
            $updateExpense->attachment = $expenseAttachmentName;
        }

        $category_ids = '';
        foreach ($request->category_ids as $category_id) {

            $category_ids .= $category_id . ', ';
        }

        $updateExpense->category_ids = $category_ids;

        $updateExpense->save();

        $adjustedExpense = $this->expenseUtil->adjustExpenseAmount($updateExpense);

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 15, data_obj: $adjustedExpense);

        $exDescriptions = ExpenseDescription::where('expense_id', $updateExpense->id)->get();

        foreach ($exDescriptions as $exDescription) {

            $exDescription->is_delete_in_update = 1;
            $exDescription->save();
        }

        $index = 0;
        foreach ($request->category_ids as $category_id) {

            $description = ExpenseDescription::where('id', $request->description_ids[$index])->first();

            if ($description) {

                $description->expense_category_id = $category_id;
                $description->amount = $request->amounts[$index];
                $description->is_delete_in_update = 0;
                $description->save();
            } else {

                $addExDescription = new ExpenseDescription();
                $addExDescription->expense_id = $updateExpense->id;
                $addExDescription->expense_category_id = $category_id;
                $addExDescription->amount = $request->amounts[$index];
                $addExDescription->save();
            }

            $index++;
        }

        $deleteAbleExDescriptions = ExpenseDescription::where('expense_id', $updateExpense->id)
            ->where('is_delete_in_update', 1)->get();

        foreach ($deleteAbleExDescriptions as  $exDescription) {

            $exDescription->delete();
        }

        // Add expense account Ledger
        $this->accountUtil->updateAccountLedger(
            voucher_type_id: 5,
            date: $request->date,
            account_id: $request->ex_account_id,
            trans_id: $updateExpense->id,
            amount: $request->net_total_amount,
            balance_type: 'debit'
        );

        return response()->json(__('Successfully expense is updated'));

    }

    // Get all form Categories by ajax request
    public function allCategories()
    {
        $categories = ExpenseCategory::orderBy('id', 'DESC')->get();
        return response()->json($categories);
    }

    // Payment view method
    public function paymentView($expenseId)
    {
        $expense = Expense::with(['branch',  'expense_payments', 'expense_payments.payment_method'])->where('id', $expenseId)->first();
        return view('expenses.ajax_view.payment_view', compact('expense'));
    }

    // Payment details
    public function paymentDetails($paymentId)
    {
        $payment = ExpensePayment::with(['expense', 'payment_method', 'expense.expense_descriptions', 'expense.expense_descriptions.category', 'expense.admin'])->where('id', $paymentId)->first();
        return view('expenses.ajax_view.payment_details', compact('payment'));
    }

    // Add expense payment modal view
    public function paymentModal($expenseId)
    {
        $expense = Expense::with('branch')->where('id', $expenseId)->first();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $methods = DB::table('payment_methods')->select('id', 'name')->get();
        return view('expenses.ajax_view.add_payment', compact('expense', 'accounts', 'methods'));
    }

    // Expense payment method
    public function payment(Request $request, $expenseId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['expense_payment'];
        $expense = Expense::where('id', $expenseId)->first();

        $addPaymentGetId = $this->expenseUtil->addPaymentGetId(
            voucher_prefix: $paymentInvoicePrefix,
            expense_id: $expense->id,
            request: $request
        );

        $this->expenseUtil->adjustExpenseAmount($expense);

        // Add Bank/Cash-in-hand Account Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 9,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $addPaymentGetId,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );


            return response()->json(__('Payment added successfully.'));


    }

    // Expense Payment edit view
    public function paymentEdit($paymentId)
    {
        $payment = ExpensePayment::with(['expense'])->where('id', $paymentId)->first();

        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('expenses.ajax_view.edit_payment', compact('payment', 'accounts', 'methods'));
    }

    // Update payment
    public function paymentUpdate(Request $request, $paymentId)
    {
        $updateExpensePayment = ExpensePayment::with('expense')->where('id', $paymentId)->first();

        if ($updateExpensePayment) {

            $this->expenseUtil->updatePayment($updateExpensePayment, $request);

            $expense = Expense::where('id', $updateExpensePayment->expense_id)
                ->select('id', 'net_total_amount', 'paid', 'due')->first();

            $this->expenseUtil->adjustExpenseAmount($expense);

            // Update Bank/Cash-In-Hand account Ledger
            $this->accountUtil->updateAccountLedger(
                voucher_type_id: 9,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $updateExpensePayment->id,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );
        }

        return response()->json(__('Payment added successfully.'));
    }

    //Delete expense payment
    public function paymentDelete(Request $request, $paymentId)
    {
        $deleteExpensePayment = ExpensePayment::where('id', $paymentId)->first();
        $storedAccountId = $deleteExpensePayment->account_id;
        $storedExpenseId = $deleteExpensePayment->expense_id;

        if (!is_null($deleteExpensePayment)) {
            // Update expense
            if ($deleteExpensePayment->attachment != null) {

                if (file_exists(public_path('uploads/payment_attachment/' . $deleteExpensePayment->attachment))) {

                    unlink(public_path('uploads/payment_attachment/' . $deleteExpensePayment->attachment));
                }
            }

            $deleteExpensePayment->delete();
        }

        $expense = Expense::where('id', $storedExpenseId)->first();
        $this->expenseUtil->adjustExpenseAmount($expense);

        if ($storedAccountId) {

            $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
        }

        return response()->json(__('Payment deleted successfully.'));
    }

    public function addQuickExpenseCategory(Request $request)
    {
        return $this->util->addQuickExpenseCategory($request);
    }
}
