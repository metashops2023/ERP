<?php

namespace App\Http\Controllers;

use App\Models\AdminUserBranch;
use App\Models\Branch;
use Carbon\Carbon;
use App\Models\CashFlow;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Utils\Converter;
use App\Utils\AccountUtil;
use App\Utils\PurchaseUtil;
use App\Utils\SupplierUtil;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\PurchaseReturn;
use App\Models\SupplierLedger;
use App\Models\PurchasePayment;
use App\Models\SupplierPayment;
use App\Utils\SupplierPaymentUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierOpeningBalance;
use App\Models\SupplierPaymentInvoice;
use App\Utils\InvoiceVoucherRefIdUtil;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\BranchWiseSupplierAmountsUtil;
use Exception;

class SupplierController extends Controller
{
    public $supplierUtil;
    public $purchaseUtil;
    public $accountUtil;
    public $invoiceVoucherRefIdUtil;
    public $converter;
    public $userActivityLogUtil;
    public $supplierPaymentUtil;
    public $branchWiseSupplierAmountsUtil;
    public function __construct(
        SupplierUtil $supplierUtil,
        PurchaseUtil $purchaseUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        Converter $converter,
        UserActivityLogUtil $userActivityLogUtil,
        SupplierPaymentUtil $supplierPaymentUtil,
        BranchWiseSupplierAmountsUtil $branchWiseSupplierAmountsUtil
    ) {
        $this->supplierUtil = $supplierUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->converter = $converter;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->supplierPaymentUtil = $supplierPaymentUtil;
        $this->branchWiseSupplierAmountsUtil = $branchWiseSupplierAmountsUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if (auth()->user()->permission->contact['supplier_all'] == '0') {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            return $this->supplierUtil->supplierListTable($request);
        }
        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }
        // $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view('contacts.suppliers.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            // 'add_branch_id' => 'required',
        ]);

        $branch = DB::table('branches')->where('id', $request->add_branch_id)->select('id', 'name', 'branch_code')->first();
        $generalSettings = DB::table('general_settings')->first('prefix');
        $firstLetterOfSupplier = str_split($request->name)[0];
        $supIdPrefix = json_decode($generalSettings->prefix, true)['supplier_id'];

        // $branchUser = getBranchUser($request->add_branch_id);
        $addSupplier = Supplier::create([
            // 'admin_user_id' => $branchUser->id,
            'admin_user_id' => auth()->user()->id,
            'branch_id' => $request->add_branch_id,
            'contact_id' => $request->contact_id ? $request->contact_id : $supIdPrefix . str_pad($this->invoiceVoucherRefIdUtil->getLastId('suppliers'), 4, "0", STR_PAD_LEFT),
            'name' => $request->name,
            'business_name' => $branch->name,
            // 'business_name' => $request->branch,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->phone,
            'landline' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'tax_number' => $request->tax_number,
            'pay_term' => $request->pay_term,
            'pay_term_number' => $request->pay_term_number,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'shipping_address' => $request->shipping_address,
            'prefix' => $request->prefix ? $request->prefix : $firstLetterOfSupplier . $this->invoiceVoucherRefIdUtil->getLastId('suppliers'),
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0,
            'total_purchase_due' => $request->opening_balance ? $request->opening_balance : 0,
        ]);

        // Add Supplier Ledger
        $this->supplierUtil->addSupplierLedger(
            voucher_type_id: 0,
            supplier_id: $addSupplier->id,
            // branch_id: auth()->user()->branch_id,
            branch_id: $request->add_branch_id,
            date: date('Y-m-d'),
            trans_id: NULL,
            amount: $request->opening_balance ? $request->opening_balance : 0.00
        );
        // $branchUser = getBranchUser($request->add_branch_id);

        $addSupplierOpeningBalance = new SupplierOpeningBalance();
        $addSupplierOpeningBalance->supplier_id = $addSupplier->id;
        // $addSupplierOpeningBalance->branch_id = auth()->user()->branch_id;
        $addSupplierOpeningBalance->branch_id = $request->add_branch_id;
        $addSupplierOpeningBalance->created_by_id = auth()->user()->id;
        $addSupplierOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
        $addSupplierOpeningBalance->save();

        return $addSupplier;
    }

    public function edit($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->select('suppliers.*')->first();
        // $branchOpeningBalance = DB::table('supplier_opening_balances')->where('supplier_id', $supplierId)
        //     ->where('branch_id', auth()->user()->branch_id)->first();
        $branchOpeningBalance = DB::table('supplier_opening_balances')->where('supplier_id', $supplierId)
            ->where('branch_id', $supplier->branch_id)->first();

        $branch = Branch::where('id', $supplier->branch_id)->get(['id', 'name', 'branch_code'])->first();

        return view('contacts.suppliers.ajax_view.edit', compact('supplier', 'branchOpeningBalance', 'branch'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            // 'update_branch_id' => 'required',
        ]);
        // $branchUser = getBranchUser($request->add_branch_id);

        $updateSupplier = Supplier::where('id', $request->id)->first();
        $previous_branch_id = $updateSupplier->branch_id;
        $updateSupplier->admin_user_id = auth()->user()->id;
        $updateSupplier->branch_id = $request->update_branch_id;
        $updateSupplier->contact_id = $request->contact_id;
        $updateSupplier->name = $request->name;
        $updateSupplier->business_name = $request->business_name;
        $updateSupplier->email = $request->email;
        $updateSupplier->phone = $request->phone;
        $updateSupplier->alternative_phone = $request->phone;
        $updateSupplier->landline = $request->phone;
        $updateSupplier->date_of_birth = $request->date_of_birth;
        $updateSupplier->tax_number = $request->tax_number;
        $updateSupplier->pay_term = $request->pay_term;
        $updateSupplier->pay_term_number = $request->pay_term_number;
        $updateSupplier->address = $request->address;
        $updateSupplier->city = $request->city;
        $updateSupplier->zip_code = $request->zip_code;
        $updateSupplier->country = $request->country;
        $updateSupplier->state = $request->state;
        $updateSupplier->shipping_address = $request->shipping_address;
        $updateSupplier->opening_balance = $request->opening_balance ? $request->opening_balance : 0;
        $updateSupplier->save();

        // Add supplier Ledger
        $this->supplierUtil->updateSupplierLedger(
            voucher_type_id: 0,
            supplier_id: $updateSupplier->id,
            previous_branch_id: $previous_branch_id,
            new_branch_id: $request->update_branch_id,
            date: $updateSupplier->created_at,
            trans_id: NULL,
            amount: $updateSupplier->opening_balance,
            fixed_date: $updateSupplier->created_at,
        );

        $branchOpeningBalance = SupplierOpeningBalance::where('supplier_id', $updateSupplier->id)
            ->where('branch_id', $request->update_branch_id)->first();

        if ($branchOpeningBalance) {

            $branchOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
            $branchOpeningBalance->save();
        } else {

            $addSupplierOpeningBalance = new SupplierOpeningBalance();
            $addSupplierOpeningBalance->supplier_id = $updateSupplier->id;
            $addSupplierOpeningBalance->branch_id = $request->update_branch_id;
            $addSupplierOpeningBalance->created_by_id = auth()->user()->id;
            $addSupplierOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
            $addSupplierOpeningBalance->save();
        }

        $calcOpeningBalance = DB::table('supplier_opening_balances')
            ->where('supplier_id', $updateSupplier->id)
            ->select(DB::raw('SUM(amount) as op_amount'))
            ->groupBy('supplier_id')->get();

        $updateSupplier->opening_balance = $calcOpeningBalance->sum('op_amount');
        $updateSupplier->save();

        $this->supplierUtil->updateSupplierLedger(
            voucher_type_id: 0,
            supplier_id: $updateSupplier->id,
            previous_branch_id: $previous_branch_id,
            new_branch_id: $request->update_branch_id,
            date: $updateSupplier->created_at,
            trans_id: NULL,
            amount: $request->opening_balance ? $request->opening_balance : 0.00,
            fixed_date: $updateSupplier->created_at,
        );

            return response()->json(__('Supplier updated successfully'));


    }

    public function delete(Request $request, $supplierId)
    {
        $deleteSupplier = Supplier::find($supplierId);

        if (!is_null($deleteSupplier)) {

            $deleteSupplier->delete();
        }

            return response()->json(__('Supplier deleted successfully'));

    }

    // Change status method
    public function changeStatus($supplierId)
    {
        $statusChange = Supplier::where('id', $supplierId)->first();
        if ($statusChange->status == 1) {

            $statusChange->status = 0;
            $statusChange->save();

            return response()->json(__('Supplier deactivated successfully'));

        } else {

            $statusChange->status = 1;
            $statusChange->save();

            return response()->json(__('Supplier activated successfully'));

        }
    }

    // Supplier view method
    public function view(Request $request, $supplierId)
    {
        $supplierId = $supplierId;
        if ($request->ajax()) {

            return $this->supplierUtil->supplierPurchaseList($request, $supplierId);
        }

        $supplier = DB::table('suppliers')->where('id', $supplierId)->first();
        if (auth()->user()->role_type == 1) {
            $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        } else if (auth()->user()->role_type == 2) {
            $branchIds = AdminUserBranch::select("branch_id")->where('admin_user_id', auth()->user()->id)->get()->toArray();
            $branches = DB::table('branches')->whereIn('id', $branchIds)->get(['id', 'name', 'branch_code']);
        } else {
            $branches = Branch::where('id', auth()->user()->branch_id)->get(['id', 'name', 'branch_code']);
        }

        return view('contacts.suppliers.view', compact('supplierId', 'supplier', 'branches'));
    }

    public function uncompletedOrders(Request $request, $supplierId)
    {
        if ($request->ajax()) {

            return $this->supplierUtil->uncompletedPurchaseOrderList($request, $supplierId);
        }
    }

    // Supplier payment list
    public function ledgers(Request $request, $supplierId)
    {
        if ($request->ajax()) {

            return $this->supplierUtil->supplierLedgers($request, $supplierId);
        }
    }

    public function ledgerPrint(Request $request, $supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->select(
            'name',
            'contact_id',
            'phone',
            'address',
        )->first();

        $supplierUtil = $this->supplierUtil;
        $branch_id = $request->branch_id;
        $ledgers = '';
        $fromDate = '';
        $toDate = '';

        $query = DB::table('supplier_ledgers')->where('supplier_ledgers.supplier_id', $supplierId)
            ->leftJoin('purchases', 'supplier_ledgers.purchase_id', 'purchases.id')
            ->leftJoin('purchase_returns', 'supplier_ledgers.purchase_return_id', 'purchase_returns.id')
            ->leftJoin('purchase_payments', 'supplier_ledgers.purchase_payment_id', 'purchase_payments.id')
            ->leftJoin('supplier_payments', 'supplier_ledgers.supplier_payment_id', 'supplier_payments.id')
            ->leftJoin('purchases as agp_purchase', 'purchase_payments.purchase_id', 'agp_purchase.id')
            ->select(
                'supplier_ledgers.report_date',
                'supplier_ledgers.voucher_type',
                'supplier_ledgers.debit',
                'supplier_ledgers.credit',
                'supplier_ledgers.running_balance',
                'purchases.invoice_id as purchase_inv_id',
                'purchases.purchase_note as purchase_par',
                'purchase_returns.invoice_id as return_inv_id',
                'purchase_returns.date as purchase_return_par',
                'purchase_payments.invoice_id as payment_voucher_no',
                'purchase_payments.note as purchase_payment_par',
                'supplier_payments.voucher_no as supplier_payment_voucher',
                'supplier_payments.less_amount',
                'supplier_payments.note as supplier_payment_par',
                'agp_purchase.invoice_id as agp_purchase',
            )->orderBy('supplier_ledgers.report_date', 'asc');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('supplier_ledgers.branch_id', NULL);
            } else {

                $query->where('supplier_ledgers.branch_id', $request->branch_id);
            }
        }

        if ($request->voucher_type) {

            $query->where('supplier_ledgers.voucher_type', $request->voucher_type); // Final
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('supplier_ledgers.report_date', $date_range); // Final

            $fromDate = $from_date;
            $toDate = $to_date;
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $ledgers = $query->orderBy('supplier_ledgers.report_date', 'asc')->get();
        } else {

            $ledgers = $query->where('supplier_ledgers.branch_id', auth()->user()->branch_id)
                ->orderBy('supplier_ledgers.report_date', 'asc')->get();
        }

        return view('contacts.suppliers.ajax_view.print_ledger', compact('branch_id', 'ledgers', 'supplier', 'supplierUtil', 'fromDate', 'toDate'));
    }

    // Supplier payment view
    public function payment($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->first();
        $branch_id = auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id;

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

        $branchWiseSupplierPurchasesAndOrders = $this->branchWiseSupplierAmountsUtil->branchWiseSupplierPurchasesAndOrders($supplierId, $branch_id);

        $amounts = $this->branchWiseSupplierAmountsUtil->branchWiseSupplierAmount($supplierId, $branch_id);

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $totalInvoiceReturnDue = DB::table('purchases')
            ->where('purchases.branch_id', auth()->user()->branch_id)
            ->where('purchases.supplier_id', $supplierId)
            ->select(DB::raw('sum(purchase_return_due) as total_return_due'))
            ->groupBy('purchases.supplier_id')->get();

        return view('contacts.suppliers.ajax_view.payment_modal', compact('supplier', 'accounts', 'methods', 'branchWiseSupplierPurchasesAndOrders', 'totalInvoiceReturnDue', 'amounts'));
    }

    // Supplier Payment add
    public function paymentAdd(Request $request, $supplierId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly

            $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
            $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];

            // Add Supplier Payment Record
            $supplierPayment = new SupplierPayment();
            $supplierPayment->voucher_no = 'SPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('supplier_payments'), 5, "0", STR_PAD_LEFT);
            $supplierPayment->reference = $request->reference;
            $supplierPayment->branch_id = auth()->user()->branch_id;
            $supplierPayment->supplier_id = $supplierId;
            $supplierPayment->account_id = $request->account_id;
            $supplierPayment->paid_amount = $request->paying_amount;
            $supplierPayment->less_amount = $request->less_amount ? $request->less_amount : 0;
            $supplierPayment->payment_method_id = $request->payment_method_id;
            $supplierPayment->date = $request->date;
            $supplierPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
            $supplierPayment->time = date('h:i:s a');
            $supplierPayment->month = date('F');
            $supplierPayment->year = date('Y');

            if ($request->hasFile('attachment')) {

                $PaymentAttachment = $request->file('attachment');
                $paymentAttachmentName = uniqid() . '-' . '.' . $PaymentAttachment->getClientOriginalExtension();
                $PaymentAttachment->move(public_path('uploads/payment_attachment/'), $paymentAttachmentName);
                $supplierPayment->attachment = $paymentAttachmentName;
            }

            $supplierPayment->note = $request->note;
            $supplierPayment->save();

            // Add supplier Ledger
            $this->supplierUtil->addSupplierLedger(
                voucher_type_id: 5,
                supplier_id: $supplierId,
                branch_id: auth()->user()->branch_id,
                date: $request->date,
                trans_id: $supplierPayment->id,
                amount: $request->paying_amount
            );

            // Add Bank/Cash-in-hand A/C Ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 19,
                date: $request->date,
                account_id: $request->account_id,
                trans_id: $supplierPayment->id,
                amount: $request->paying_amount,
                balance_type: 'debit'
            );

            if (isset($request->purchase_ids)) {

                $this->supplierPaymentUtil->specificPurchaseOrOrderByPayment($request, $supplierPayment, $supplierId, $paymentInvoicePrefix);
            } else {

                $this->supplierPaymentUtil->randomPurchaseOrOrderPayment($request, $supplierPayment, $supplierId, $paymentInvoicePrefix);
            }

            $this->supplierUtil->adjustSupplierForPurchasePaymentDue($supplierId);

            $payment = DB::table('supplier_payments')
                ->where('supplier_payments.id', $supplierPayment->id)
                ->leftJoin('suppliers', 'supplier_payments.supplier_id', 'suppliers.id')
                ->leftJoin('payment_methods', 'supplier_payments.payment_method_id', 'payment_methods.id')
                ->select(
                    'supplier_payments.voucher_no',
                    'supplier_payments.date',
                    'supplier_payments.paid_amount',
                    'suppliers.name as supplier',
                    'suppliers.phone',
                    'payment_methods.name as method',
                )->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 28, data_obj: $payment);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }


        return response()->json(__('Payment added successfully.'));
    }

    public function returnPayment($supplierId)
    {
        $supplier = DB::table('suppliers')->where('id', $supplierId)->first();

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

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        return view('contacts.suppliers.ajax_view.return_payment_modal', compact('supplier', 'accounts', 'methods'));
    }

    public function returnPaymentAdd(Request $request, $supplierId)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ]);

        // Add Supplier Payment Record
        $supplierPayment = new SupplierPayment();
        $supplierPayment->voucher_no = 'RPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('supplier_payments'), 5, "0", STR_PAD_LEFT);
        $supplierPayment->branch_id = auth()->user()->branch_id;
        $supplierPayment->supplier_id = $supplierId;
        $supplierPayment->account_id = $request->account_id;
        $supplierPayment->paid_amount = $request->paying_amount;
        $supplierPayment->type = 2;
        $supplierPayment->payment_method_id = $request->payment_method_id;
        $supplierPayment->date = $request->date;
        $supplierPayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $supplierPayment->time = date('h:i:s a');
        $supplierPayment->month = date('F');
        $supplierPayment->year = date('Y');

        if ($request->hasFile('attachment')) {

            $PaymentAttachment = $request->file('attachment');
            $paymentAttachmentName = uniqid() . '-' . '.' . $PaymentAttachment->getClientOriginalExtension();
            $PaymentAttachment->move(public_path('uploads/payment_attachment/'), $paymentAttachmentName);
            $supplierPayment->attachment = $paymentAttachmentName;
        }

        $supplierPayment->note = $request->note;
        $supplierPayment->save();

        // Add supplier Ledger
        $this->supplierUtil->addSupplierLedger(
            voucher_type_id: 6,
            supplier_id: $supplierId,
            branch_id: auth()->user()->branch_id,
            date: $request->date,
            trans_id: $supplierPayment->id,
            amount: $request->paying_amount
        );

        // Add Bank A/C Ledger
        $this->accountUtil->addAccountLedger(
            voucher_type_id: 21,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $supplierPayment->id,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );

        $returnPurchases = Purchase::with(['purchase_return'])
            ->where('branch_id', auth()->user()->branch_id)
            ->where('purchase_return_due', '>', 0)
            ->orderBy('report_date', 'asc')
            ->get();

        if (count($returnPurchases) > 0) {

            $index = 0;
            foreach ($returnPurchases as $returnPurchase) {

                if ($returnPurchase->purchase_return_due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        // Add purchase payment
                        $addPurchasePayment = new PurchasePayment();

                        $addPurchasePayment->invoice_id = 'RPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);

                        $addPurchasePayment->purchase_id = $returnPurchase->id;
                        $addPurchasePayment->supplier_id = $supplierId;
                        $addPurchasePayment->branch_id = auth()->user()->branch_id;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->payment_method_id = $request->payment_method_id;
                        $addPurchasePayment->paid_amount = $request->paying_amount;
                        $addPurchasePayment->payment_type = 2;
                        $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->note = $request->note;

                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->save();

                        // Add Supplier return Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $returnPurchase->id;
                        $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                        $addSupplierPaymentInvoice->type = 2;
                        $addSupplierPaymentInvoice->save();

                        if ($returnPurchase->purchase_return) {

                            $returnPurchase->purchase_return->total_return_due -= $request->paying_amount;
                            $returnPurchase->purchase_return->total_return_due_received += $request->paying_amount;
                            $returnPurchase->purchase_return->save();
                        }

                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($returnPurchase);
                    }
                } elseif ($returnPurchase->purchase_return_due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        // Add purchase payment
                        $addPurchasePayment = new PurchasePayment();

                        $addPurchasePayment->invoice_id = 'RPV' . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);

                        $addPurchasePayment->purchase_id = $returnPurchase->id;;
                        $addPurchasePayment->supplier_id = $supplierId;
                        $addPurchasePayment->branch_id = auth()->user()->branch_id;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->payment_method_id = $request->payment_method_id;
                        $addPurchasePayment->paid_amount = $request->paying_amount;
                        $addPurchasePayment->payment_type = 2;
                        $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->note = $request->note;
                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->save();

                        // Add Supplier return Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $returnPurchase->id;
                        $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                        $addSupplierPaymentInvoice->type = 2;
                        $addSupplierPaymentInvoice->save();

                        if ($returnPurchase->purchase_return) {

                            $returnPurchase->purchase_return->total_return_due -= $request->paying_amount;
                            $returnPurchase->purchase_return->total_return_due_received += $request->paying_amount;
                            $returnPurchase->purchase_return->save();
                        }

                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($returnPurchase);
                    }
                } elseif ($returnPurchase->purchase_return_due < $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        // Add purchase payment
                        $addPurchasePayment = new PurchasePayment();

                        $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');

                        $addPurchasePayment->purchase_id = $returnPurchase->id;
                        $addPurchasePayment->supplier_id = $supplierId;
                        $addPurchasePayment->branch_id = auth()->user()->branch_id;
                        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                        $addPurchasePayment->account_id = $request->account_id;
                        $addPurchasePayment->payment_method_id = $request->payment_method_id;
                        $addPurchasePayment->paid_amount = $returnPurchase->purchase_return_due;
                        $addPurchasePayment->payment_type = 2;
                        $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                        $addPurchasePayment->month = date('F');
                        $addPurchasePayment->year = date('Y');
                        $addPurchasePayment->note = $request->note;
                        $addPurchasePayment->admin_id = auth()->user()->id;
                        $addPurchasePayment->save();

                        // Add Supplier return Payment invoice
                        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                        $addSupplierPaymentInvoice->purchase_id = $returnPurchase->id;
                        $addSupplierPaymentInvoice->paid_amount = $returnPurchase->purchase_return_due;
                        $addSupplierPaymentInvoice->type = 2;
                        $addSupplierPaymentInvoice->save();

                        if ($returnPurchase->purchase_return) {

                            $returnPurchase->purchase_return->total_return_due -= $returnPurchase->purchase_return_due;
                            $returnPurchase->purchase_return->total_return_due_received += $returnPurchase->purchase_return_due;
                            $returnPurchase->purchase_return->save();
                        }

                        $request->paying_amount -= $returnPurchase->purchase_return_due;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($returnPurchase);
                    }
                }
                $index++;
            }
        }

        if ($request->paying_amount > 0) {

            $dueSupplierReturnInvoices = PurchaseReturn::where('supplier_id', $supplierId)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('total_return_due', '>', 0)
                ->where('purchase_id', NULL)
                ->get();

            if (count($dueSupplierReturnInvoices) > 0) {

                $index = 0;
                foreach ($dueSupplierReturnInvoices as $dueSupplierReturnInvoice) {

                    if ($dueSupplierReturnInvoice->total_return_due > $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $dueSupplierReturnInvoice->total_return_due -= $request->paying_amount;
                            $dueSupplierReturnInvoice->total_return_due_received += $request->paying_amount;
                            $dueSupplierReturnInvoice->save();

                            // Add purchase payment
                            $addPurchasePayment = new PurchasePayment();

                            $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');

                            $addPurchasePayment->supplier_id = $supplierId;
                            $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                            $addPurchasePayment->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addPurchasePayment->account_id = $request->account_id;
                            $addPurchasePayment->payment_method_id = $request->payment_method_id;
                            $addPurchasePayment->paid_amount = $request->paying_amount;
                            $addPurchasePayment->payment_type = 2;
                            $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                            $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                            $addPurchasePayment->month = date('F');
                            $addPurchasePayment->year = date('Y');
                            $addPurchasePayment->note = $request->note;
                            $addPurchasePayment->admin_id = auth()->user()->id;
                            $addPurchasePayment->save();

                            // Add Supplier return Payment invoice
                            $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                            $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                            $addSupplierPaymentInvoice->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                            $addSupplierPaymentInvoice->type = 2;
                            $addSupplierPaymentInvoice->save();
                            $request->paying_amount -= $request->paying_amount;
                        }
                    } elseif ($dueSupplierReturnInvoice->total_return_due == $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $dueSupplierReturnInvoice->total_return_due -= $request->paying_amount;
                            $dueSupplierReturnInvoice->total_return_due_received += $request->paying_amount;
                            $dueSupplierReturnInvoice->save();
                            // Add purchase payment
                            $addPurchasePayment = new PurchasePayment();

                            $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');

                            $addPurchasePayment->supplier_id = $supplierId;
                            $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                            $addPurchasePayment->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addPurchasePayment->account_id = $request->account_id;
                            $addPurchasePayment->payment_method_id = $request->payment_method_id;
                            $addPurchasePayment->paid_amount = $request->paying_amount;
                            $addPurchasePayment->payment_type = 2;
                            $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                            $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                            $addPurchasePayment->month = date('F');
                            $addPurchasePayment->year = date('Y');
                            $addPurchasePayment->note = $request->note;
                            $addPurchasePayment->admin_id = auth()->user()->id;
                            $addPurchasePayment->save();

                            // Add Supplier return Payment invoice
                            $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                            $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                            $addSupplierPaymentInvoice->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addSupplierPaymentInvoice->paid_amount = $request->paying_amount;
                            $addSupplierPaymentInvoice->type = 2;
                            $addSupplierPaymentInvoice->save();
                            $request->paying_amount -= $request->paying_amount;
                        }
                    } elseif ($dueSupplierReturnInvoice->total_return_due < $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $dueSupplierReturnInvoice->total_return_due -=  $dueSupplierReturnInvoice->total_return_due;
                            $dueSupplierReturnInvoice->total_return_due_received +=  $dueSupplierReturnInvoice->total_return_due;
                            $dueSupplierReturnInvoice->save();
                            // Add purchase payment
                            $addPurchasePayment = new PurchasePayment();

                            $addPurchasePayment->invoice_id = 'RPV' . $this->invoiceVoucherRefIdUtil->getLastId('purchase_payments');

                            $addPurchasePayment->supplier_id = $supplierId;
                            $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
                            $addPurchasePayment->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addPurchasePayment->account_id = $request->account_id;
                            $addPurchasePayment->payment_method_id = $request->payment_method_id;
                            $addPurchasePayment->paid_amount = $dueSupplierReturnInvoice->total_return_due;
                            $addPurchasePayment->payment_type = 2;
                            $addPurchasePayment->date = date('d-m-y', strtotime($request->date));
                            $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
                            $addPurchasePayment->month = date('F');
                            $addPurchasePayment->year = date('Y');
                            $addPurchasePayment->note = $request->note;
                            $addPurchasePayment->admin_id = auth()->user()->id;
                            $addPurchasePayment->save();

                            // Add Supplier return Payment invoice
                            $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
                            $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
                            $addSupplierPaymentInvoice->supplier_return_id = $dueSupplierReturnInvoice->id;
                            $addSupplierPaymentInvoice->paid_amount = $dueSupplierReturnInvoice->total_return_due;
                            $addSupplierPaymentInvoice->type = 2;
                            $addSupplierPaymentInvoice->save();
                            $request->paying_amount -= $dueSupplierReturnInvoice->total_return_due;
                        }
                    }
                    $index++;
                }
            }
        }

        $this->supplierUtil->adjustSupplierForPurchasePaymentDue($supplierId);

        return response()->json(__('Return amount received successfully.'));

    }

    // Supplier Payment Details
    public function paymentDetails($paymentId)
    {
        $supplierPayment = SupplierPayment::with(
            'branch',
            'supplier',
            'account',
            'supplier_payment_invoices',
            'supplier_payment_invoices.purchase:id,invoice_id,date',
            'paymentMethod:id,name'
        )->where('id', $paymentId)->first();

        return view('contacts.suppliers.ajax_view.payment_details', compact('supplierPayment'));
    }

    // Supplier Payment Delete
    public function paymentDelete(Request $request, $paymentId)
    {
        $deleteSupplierPayment = SupplierPayment::with('supplier_payment_invoices')->where('id', $paymentId)->first();

        $storedAccountId = $deleteSupplierPayment->account_id;
        $storedSupplierPayment = $deleteSupplierPayment;
        $storeSupplierPaymentInvoices = $deleteSupplierPayment->supplier_payment_invoices;

        if ($deleteSupplierPayment->attachment != null) {

            if (file_exists(public_path('uploads/payment_attachment/' . $deleteSupplierPayment->attachment))) {

                unlink(public_path('uploads/payment_attachment/' . $deleteSupplierPayment->attachment));
            }
        }

        $payment = DB::table('supplier_payments')
            ->where('supplier_payments.id', $deleteSupplierPayment->id)
            ->leftJoin('suppliers', 'supplier_payments.supplier_id', 'suppliers.id')
            ->leftJoin('payment_methods', 'supplier_payments.payment_method_id', 'payment_methods.id')
            ->select(
                'supplier_payments.voucher_no',
                'supplier_payments.date',
                'supplier_payments.paid_amount',
                'suppliers.name as supplier',
                'suppliers.phone',
                'payment_methods.name as method',
            )->first();

        if ($deleteSupplierPayment->type == 1) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 28, data_obj: $payment);
        }

        $deleteSupplierPayment->delete();

        // Update supplier payment invoices
        if (count($storeSupplierPaymentInvoices) > 0) {

            if ($storedSupplierPayment->type == 1) {

                foreach ($storeSupplierPaymentInvoices as $pInvoice) {

                    $purchase = Purchase::where('id', $pInvoice->purchase_id)->first();
                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                }
            } else {

                foreach ($storeSupplierPaymentInvoices as $pInvoice) {

                    if ($pInvoice->purchase_id) {

                        $purchase = Purchase::with('purchase_return')->where('id', $pInvoice->purchase_id)->first();
                        if ($purchase->purchase_return) {

                            $purchase->purchase_return->total_return_due += $pInvoice->paid_amount;
                            $purchase->purchase_return->total_return_due_received -= $pInvoice->paid_amount;
                            $purchase->purchase_return->save();
                        }
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                    } elseif ($pInvoice->supplier_return_id) {

                        $supplierReturn = PurchaseReturn::where('id', $pInvoice->supplier_return_id)->first();
                        $supplierReturn->total_return_due += $pInvoice->paid_amount;
                        $supplierReturn->total_return_due_received -= $pInvoice->paid_amount;
                        $supplierReturn->save();
                    }
                }
            }
        }

        if ($storedAccountId) {

            $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
        }

        $this->supplierUtil->adjustSupplierForPurchasePaymentDue($deleteSupplierPayment->supplier_id);

        return response()->json(__('Payment deleted successfully.'));

    }

    public function allPaymentList(Request $request, $supplierId)
    {
        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $payments = '';
            $paymentsQuery = DB::table('supplier_ledgers')->where('supplier_ledgers.supplier_id', $supplierId)->whereIn('supplier_ledgers.voucher_type', [3, 4, 5, 6])
                ->leftJoin('supplier_payments', 'supplier_ledgers.supplier_payment_id', 'supplier_payments.id')
                ->leftJoin('payment_methods as sp_pay_method', 'supplier_payments.payment_method_id', 'sp_pay_method.id')
                ->leftJoin('accounts as sp_account', 'supplier_payments.account_id', 'sp_account.id')
                ->leftJoin('purchase_payments', 'supplier_ledgers.purchase_payment_id', 'purchase_payments.id')
                ->leftJoin('payment_methods as pp_pay_method', 'purchase_payments.payment_method_id', 'pp_pay_method.id')
                ->leftJoin('accounts as pp_account', 'purchase_payments.account_id', 'pp_account.id')
                ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
                ->leftJoin('purchase_returns', 'purchase_payments.supplier_return_id', 'purchase_returns.id')
                // ->leftJoin('admin_and_users', 'supplier_ledgers.user_id', 'admin_and_users.id')
            ;

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $paymentsQuery->where('supplier_ledgers.branch_id', NULL);
                } else {

                    $paymentsQuery->where('supplier_ledgers.branch_id', $request->branch_id);
                }
            }

            if ($request->p_from_date) {

                $from_date = date('Y-m-d', strtotime($request->p_from_date));
                $to_date = $request->p_to_date ? date('Y-m-d', strtotime($request->p_to_date)) : $from_date;
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $paymentsQuery->whereBetween('supplier_ledgers.report_date', $date_range); // Final
            }

            $payments = $paymentsQuery->select(
                'supplier_ledgers.date',
                'supplier_ledgers.report_date',
                'supplier_ledgers.amount',
                'supplier_ledgers.supplier_payment_id',
                'supplier_ledgers.purchase_payment_id',
                'supplier_ledgers.voucher_type',
                'supplier_payments.voucher_no as supplier_payment_voucher',
                'supplier_payments.reference',
                'supplier_payments.pay_mode as sp_pay_mode',
                'supplier_payments.less_amount',
                'sp_pay_method.name as sp_payment_method',
                'sp_account.name as sp_account',
                'sp_account.account_number as sp_account_number',
                'purchase_payments.invoice_id as purchase_payment_voucher',
                'purchase_payments.pay_mode as pp_pay_mode',
                'pp_pay_method.name as pp_payment_method',
                'pp_account.name as pp_account',
                'pp_account.account_number as pp_account_number',
                'purchases.invoice_id as purchase_inv',
                'purchase_returns.invoice_id as return_inv',
            )->orderBy('supplier_ledgers.report_date', 'desc');

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $payments = $paymentsQuery->orderBy('supplier_ledgers.report_date', 'desc');
            } else {

                $payments = $paymentsQuery->where('supplier_ledgers.branch_id', auth()->user()->branch_id)->orderBy('supplier_ledgers.report_date', 'desc');
            }

            return DataTables::of($payments)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';

                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__("Action").' </button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if ($row->supplier_payment_id) {

                        $html .= '<a href="' . route('suppliers.view.details', $row->supplier_payment_id) . '" id="payment_details" class="dropdown-item"><i class="fas fa-eye text-primary"></i> '.__("Details").'</a>';

                        $html .= '<a href="' . route('suppliers.payment.delete', $row->supplier_payment_id) . '" id="delete_payment" class="dropdown-item"><i class="far fa-trash-alt text-danger"></i> '.__("Delete").'</a>';
                    } else {

                        $html .= '<a href="' . route('purchases.payment.details', $row->purchase_payment_id) . '" id="payment_details" class="dropdown-item"><i class="fas fa-eye text-primary"></i> '.__("Details").'</a>';

                        $html .= '<a href="' . route('purchases.payment.delete', $row->purchase_payment_id) . '" id="delete_payment" class="dropdown-item"><i class="far fa-trash-alt text-danger"></i>'.__("Delete").' </a>';
                    }



                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('voucher_no', function ($row) {

                    return $row->supplier_payment_voucher . $row->purchase_payment_voucher;
                })
                ->editColumn('against_invoice', function ($row) {

                    if ($row->purchase_inv || $row->return_inv) {

                        if ($row->purchase_inv) {

                            return 'Purchase : ' . $row->purchase_inv;
                        } else {

                            return 'Purchase Return : ' . $row->return_inv;
                        }
                    } else {
                        if ($row->supplier_payment_id) {

                            return '<a href="' . route('suppliers.view.details', $row->supplier_payment_id) . '" id="payment_details" class="btn btn-sm text-info"> Details</a>';
                        } else {

                            return '<a href="' . route('purchases.payment.details', $row->purchase_payment_id) . '" id="payment_details" class="btn btn-sm text-info"> Details</a>';
                        }
                    }
                })
                ->editColumn('type', function ($row) {

                    if ($row->voucher_type == 3 || $row->voucher_type == 5) {

                        return 'Payment';
                    } else {

                        return 'Return Payment';
                    }
                })
                ->editColumn('method', function ($row) {

                    return $row->sp_pay_mode . $row->sp_payment_method . $row->pp_pay_mode . $row->pp_payment_method;
                })
                ->editColumn('account', function ($row) {

                    if ($row->sp_account) {

                        return $row->sp_account . '(A/C:' . $row->sp_account_number . ')';
                    } else {

                        return $row->pp_account . '(A/C:' . $row->pp_account_number . ')';
                    }
                })
                ->editColumn('less_amount', fn ($row) => '<span class="less_amount" data-value="' . $row->less_amount . '">' . $this->converter->format_in_bdt($row->less_amount) . '</span>')

                ->editColumn('amount', fn ($row) => '<span class="amount" data-value="' . $row->amount . '">' . $this->converter->format_in_bdt($row->amount) . '</span>')

                ->rawColumns(['date', 'against_invoice', 'type', 'method', 'account', 'less_amount', 'amount', 'action'])
                ->make(true);
        }
    }

    public function allPaymentPrint(Request $request, $supplierId)
    {
        $payments = '';
        $fromDate  = '';
        $toDate = '';
        $supplier = DB::table('suppliers')->where('id', $supplierId)->first();

        $paymentsQuery = DB::table('supplier_ledgers')
            ->where('supplier_ledgers.supplier_id', $supplierId)
            ->whereIn('supplier_ledgers.voucher_type', [3, 4, 5, 6])
            ->leftJoin('supplier_payments', 'supplier_ledgers.supplier_payment_id', 'supplier_payments.id')
            ->leftJoin('payment_methods as sp_pay_method', 'supplier_payments.payment_method_id', 'sp_pay_method.id')
            ->leftJoin('accounts as sp_account', 'supplier_payments.account_id', 'sp_account.id')
            ->leftJoin('purchase_payments', 'supplier_ledgers.purchase_payment_id', 'purchase_payments.id')
            ->leftJoin('payment_methods as pp_pay_method', 'purchase_payments.payment_method_id', 'pp_pay_method.id')
            ->leftJoin('accounts as pp_account', 'purchase_payments.account_id', 'pp_account.id')
            ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
            ->leftJoin('purchase_returns', 'purchase_payments.supplier_return_id', 'purchase_returns.id')
            // ->leftJoin('admin_and_users', 'supplier_ledgers.user_id', 'admin_and_users.id')
        ;

        if ($request->type) {

            if ($request->type == 1) {

                $paymentsQuery->whereIn('supplier_ledgers.voucher_type', [3, 5]);
            } else {

                $paymentsQuery->whereIn('supplier_ledgers.voucher_type', [4, 6]);
            }
        }

        if ($request->p_from_date) {

            $from_date = date('Y-m-d', strtotime($request->p_from_date));
            $to_date = $request->p_to_date ? date('Y-m-d', strtotime($request->p_to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $paymentsQuery->whereBetween('supplier_ledgers.report_date', $date_range); // Final

            $fromDate  = $request->p_from_date;
            $toDate = $request->p_to_date ? $request->p_to_date : $request->p_from_date;
        }

        $payments = $paymentsQuery->select(
            'supplier_ledgers.date',
            'supplier_ledgers.report_date',
            'supplier_ledgers.amount',
            'supplier_ledgers.supplier_payment_id',
            'supplier_ledgers.purchase_payment_id',
            'supplier_ledgers.voucher_type',
            'supplier_payments.voucher_no as supplier_payment_voucher',
            'supplier_payments.reference',
            'supplier_payments.pay_mode as sp_pay_mode',
            'supplier_payments.less_amount',
            'sp_pay_method.name as sp_payment_method',
            'sp_account.name as sp_account',
            'sp_account.account_number as sp_account_number',
            'purchase_payments.invoice_id as purchase_payment_voucher',
            'purchase_payments.pay_mode as pp_pay_mode',
            'pp_pay_method.name as pp_payment_method',
            'pp_account.name as pp_account',
            'pp_account.account_number as pp_account_number',
            'purchases.invoice_id as purchase_inv',
            'purchase_returns.invoice_id as return_inv',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $payments = $paymentsQuery->orderBy('supplier_ledgers.report_date', 'desc')->get();
        } else {

            $payments = $paymentsQuery->where('supplier_ledgers.branch_id', auth()->user()->branch_id)->orderBy('supplier_ledgers.report_date', 'desc')->get();
        }

        return view('contacts.suppliers.ajax_view.print_payments', compact('payments', 'fromDate', 'toDate', 'supplier'));
    }

    public function supplierAmountsBranchWise(Request $request, $supplierId)
    {
        return $this->branchWiseSupplierAmountsUtil->branchWiseSupplierAmount($supplierId, $request->branch_id, $request->from_date, $request->to_date);
    }
}
