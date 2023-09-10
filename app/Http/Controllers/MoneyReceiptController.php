<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Account;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Models\SalePayment;
use App\Models\MoneyReceipt;
use Illuminate\Http\Request;
use App\Models\CustomerLedger;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Support\Facades\DB;

class MoneyReceiptController extends Controller
{
    protected $invoiceVoucherRefIdUtil;
    public function __construct(InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function moneyReceiptList($customerId)
    {
        $customer = Customer::with('receipts', 'receipts.branch')
            ->where('id', $customerId)
            ->first();
        return view('contacts.customers.ajax_view.money_receipt_list', compact('customer'));
    }

    public function moneyReceiptPrint($receiptId)
    {
        $receipt = DB::table('money_receipts')
            ->leftJoin('customers', 'money_receipts.customer_id', 'customers.id')
            ->leftJoin('branches', 'money_receipts.branch_id', 'branches.id')
            ->select(
                'money_receipts.*',
                'customers.name as cus_name',
                'branches.name as branch_name',
                'branches.branch_code',
                'branches.city',
                'branches.state',
                'branches.zip_code',
                'branches.email',
                'branches.phone',
                'branches.country',
                'branches.logo',
            )->where('money_receipts.id', $receiptId)->first();
        return view('contacts.customers.ajax_view.print_receipt', compact('receipt'));
    }

    public function moneyReceiptCreate($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)->first();
        $branches = DB::table('branches')->get();
        return view('contacts.customers.ajax_view.money_receipt_add_modal', compact('customer', 'branches'));
    }

    public function store(Request $request, $customerId)
    {
        $addReceipt = new MoneyReceipt();
        $addReceipt->invoice_id = 'MR'.str_pad($this->invoiceVoucherRefIdUtil->getLastId('money_receipts'), 4, "0", STR_PAD_LEFT);
        $addReceipt->customer_id = $customerId;
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $addReceipt->branch_id = NULL;
        } else {
            $addReceipt->branch_id = auth()->user()->branch_id;
        }

        $addReceipt->amount = $request->amount;
        $addReceipt->note = $request->note;
        $addReceipt->receiver = $request->receiver;
        $addReceipt->ac_details = $request->ac_details;
        $addReceipt->is_date = isset($request->is_date) ? 1 : 0;
        $addReceipt->is_customer_name = isset($request->is_customer_name) ? 1 : 0;
        $addReceipt->is_header_less = isset($request->is_header_less) ? 1 : 0;
        $addReceipt->gap_from_top = isset($request->is_header_less) ? $request->gap_from_top : NULL;
        $addReceipt->date = date('d-m-Y');
        $addReceipt->save();

        $receipt = DB::table('money_receipts')
            ->leftJoin('customers', 'money_receipts.customer_id', 'customers.id')
            ->leftJoin('branches', 'money_receipts.branch_id', 'branches.id')
            ->select(
                'money_receipts.*',
                'customers.name as cus_name',
                'branches.name as branch_name',
                'branches.branch_code',
                'branches.city',
                'branches.state',
                'branches.zip_code',
                'branches.email',
                'branches.phone',
                'branches.country',
                'branches.logo',
            )->where('money_receipts.id', $addReceipt->id)->first();
        return view('contacts.customers.ajax_view.print_receipt', compact('receipt'));
    }

    public function update(Request $request, $receiptId)
    {
        $updateReceipt = MoneyReceipt::where('id', $receiptId)->first();
        $updateReceipt->amount = $request->amount;
        $updateReceipt->note = $request->note;
        $updateReceipt->receiver = $request->receiver;
        $updateReceipt->ac_details = $request->ac_details;
        $updateReceipt->is_date = isset($request->is_date) ? 1 : 0;
        $updateReceipt->is_header_less = isset($request->is_header_less) ? 1 : 0;
        $updateReceipt->gap_from_top = isset($request->is_header_less) ? $request->gap_from_top : NULL;
        $updateReceipt->is_customer_name = isset($request->is_customer_name) ? 1 : 0;
        $updateReceipt->date = date('d-m-Y');
        $updateReceipt->save();

        $receipt = DB::table('money_receipts')
            ->leftJoin('customers', 'money_receipts.customer_id', 'customers.id')
            ->leftJoin('branches', 'money_receipts.branch_id', 'branches.id')
            ->select(
                'money_receipts.*',
                'customers.name as cus_name',
                'branches.name as branch_name',
                'branches.branch_code',
                'branches.city',
                'branches.state',
                'branches.zip_code',
                'branches.email',
                'branches.phone',
                'branches.country',
                'branches.logo',
            )->where('money_receipts.id', $receiptId)->first();
        return view('contacts.customers.ajax_view.print_receipt', compact('receipt'));
    }

    public function edit($receiptId)
    {
        $receipt = DB::table('money_receipts')
            ->leftJoin('customers', 'money_receipts.customer_id', 'customers.id')
            ->select(
                'money_receipts.*',
                'customers.name as cus_name',
                'customers.phone as cus_phone',
                'customers.business_name as cus_business',
            )->where('money_receipts.id', $receiptId)->first();

        return view('contacts.customers.ajax_view.money_receipt_edit_modal', compact('receipt'));
    }

    public function changeStatusModal($receiptId)
    {
        $receipt = DB::table('money_receipts')->where('id', $receiptId)->first();
        $accounts = DB::table('accounts')->get();
        return view('contacts.customers.ajax_view.change_receipt_status_modal', compact('receipt', 'accounts'));
    }

    public function changeStatus(Request $request, $receiptId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        $receipt = MoneyReceipt::where('id', $receiptId)->first();
        $receipt->received_amount = $request->amount;
        $receipt->payment_method = $request->payment_method;
        $receipt->status = 'Completed';
        $receipt->save();

        $customer = Customer::where('id', $receipt->customer_id)->first();
        $customer->total_paid += $request->amount;
        $customer->total_sale_due -= $request->amount;
        $customer->save();

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) { $invoiceId .= rand(1, 9);$a++; }

        $dueInvoices = Sale::where('customer_id', $receipt->customer_id)->where('due', '>', 0)->get();
        if (count($dueInvoices) > 0) {
            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {
                if ($dueInvoice->due > $request->amount) {
                    $dueInvoice->paid += $request->amount;
                    $dueInvoice->due -= $request->amount;
                    $dueInvoice->save();
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $dueInvoice->id;
                    $addSalePayment->customer_id = $receipt->customer_id;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->paid_amount = $request->amount;
                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                    $addSalePayment->time = date('h:i:s a');
                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->pay_mode = $request->payment_method;
                    $addSalePayment->admin_id = auth()->user()->id;
                    $addSalePayment->payment_on = 1;
                    $addSalePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->credit += $request->amount;
                        $account->balance += $request->amount;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $request->amount;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($dueInvoice->customer_id) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $receipt->customer_id;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }

                    //$dueAmounts -= $dueAmounts;
                    if ($index == 1) {
                        $request->amount = 0;
                        break;
                    }
                } elseif ($dueInvoice->due == $request->amount) {
                    $dueInvoice->paid += $request->amount;
                    $dueInvoice->due -= $request->amount;
                    $dueInvoice->save();
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $dueInvoice->id;
                    $addSalePayment->customer_id = $receipt->customer_id;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->paid_amount = $request->amount;
                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                    $addSalePayment->time = date('h:i:s a');
                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->pay_mode = $request->payment_method;
                    $addSalePayment->admin_id = auth()->user()->id;
                    $addSalePayment->payment_on = 1;
                    $addSalePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->debit += $request->amount;
                        $account->balance += $request->amount;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $request->amount;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($dueInvoice->customer_id) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $receipt->customer_id;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }

                    if ($index == 1) {
                        $request->amount = 0;
                        break;
                    }
                } elseif ($dueInvoice->due < $request->amount) {
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $dueInvoice->id;
                    $addSalePayment->customer_id = $receipt->customer_id;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->paid_amount = $dueInvoice->due;
                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                    $addSalePayment->time = date('h:i:s a');
                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->pay_mode = $request->payment_method;

                    $addSalePayment->admin_id = auth()->user()->id;
                    $addSalePayment->payment_on = 1;
                    $addSalePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->credit += $dueInvoice->due;
                        $account->balance += $dueInvoice->due;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $dueInvoice->due;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($dueInvoice->customer_id) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $receipt->customer_id;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }

                    $request->amount -= $dueInvoice->due;
                    $dueInvoice->paid += $dueInvoice->due;
                    $dueInvoice->due -= $dueInvoice->due;
                    $dueInvoice->save();
                }
                $index++;
            }
        }
        return response()->json('Successfully money receipt voucher is completed.');
    }

    public function delete($receiptId)
    {
        $delete = MoneyReceipt::find($receiptId);
        if (!is_null($delete)) {
            $delete->delete();
        }
        return response()->json('Successfully money receipt voucher is deleted');
    }
}
