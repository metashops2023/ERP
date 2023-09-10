<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Utils\LoanUtil;
use App\Models\CashFlow;
use App\Utils\AccountUtil;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LoanPaymentDistribution;
use App\Utils\InvoiceVoucherRefIdUtil;

class LoanPaymentController extends Controller
{
    protected $accountUtil;
    protected $loanUtil;
    protected $invoiceVoucherRefIdUtil;
    public function __construct(
        AccountUtil $accountUtil,
        LoanUtil $loanUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil
    ) {
        $this->accountUtil = $accountUtil;
        $this->loanUtil = $loanUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function loanAdvanceReceiveModal($company_id)
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        $company = DB::table('loan_companies')->where('id', $company_id)->first();

        return view('accounting.loans.ajax_view.loan_advance_receive_modal', compact('accounts', 'company', 'methods'));
    }

    public function loanAdvanceReceiveStore(Request $request, $company_id)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ], [
            'payment_method_id|required' => 'Please select a payment method.',
            'account_id|required' => 'Please select debit account.',
        ]);

        $loanPayment = new LoanPayment();
        $loanPayment->voucher_no = 'LAR' . $this->invoiceVoucherRefIdUtil->getLastId('loan_payments');
        $loanPayment->company_id = $company_id;
        $loanPayment->payment_type = 1;
        $loanPayment->branch_id = auth()->user()->branch_id;
        $loanPayment->account_id = $request->account_id;
        $loanPayment->user_id = auth()->user()->id;
        $loanPayment->paid_amount = $request->paying_amount;
        $loanPayment->payment_method_id = $request->payment_method_id;
        $loanPayment->date = $request->date;
        $loanPayment->report_date = date('Y-m-d', strtotime($request->date));
        $loanPayment->save();

        $this->accountUtil->addAccountLedger(
            voucher_type_id: 16,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $loanPayment->id,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );

        $dueLoans = Loan::where('type', 1)->where('loan_company_id', $company_id)->where('due', '>', 0)->get();
        
        foreach ($dueLoans as $dueLoan) {
            if ($dueLoan->due > $request->paying_amount) {

                if ($request->paying_amount > 0) {

                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $request->paying_amount, 1);

                    $request->paying_amount -= $request->paying_amount;

                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                } else {

                    break;
                }
            } elseif ($dueLoan->due == $request->paying_amount) {

                if ($request->paying_amount > 0) {

                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $request->paying_amount, 1);

                    $request->paying_amount -= $request->paying_amount;

                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                } else {

                    break;
                }
            } elseif ($dueLoan->due < $request->paying_amount) {

                if ($request->paying_amount > 0) {

                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $dueLoan->due, 1);
                    $request->paying_amount -= $dueLoan->due;
                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                } else {
                    
                    break;
                }
            }
        }

        $this->loanUtil->adjustCompanyLoanAdvanceAmount($company_id);

        return response()->json('Loan&Advance received Successfully');
    }

    public function loaLiabilityPaymentModal($company_id)
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        $company = DB::table('loan_companies')->where('id', $company_id)->first();

        return view('accounting.loans.ajax_view.loan_liability_pay_modal', compact('accounts', 'company', 'methods'));
    }

    public function loanLiabilityPaymentStore(Request $request, $company_id)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ], [
            'payment_method_id|required' => 'Please select a payment method.',
            'account_id|required' => 'Please select debit account.',
        ]);
        
        $loanPayment = new LoanPayment();
        $loanPayment->voucher_no = 'LLP' . $this->invoiceVoucherRefIdUtil->getLastId('loan_payments');
        $loanPayment->company_id = $company_id;
        $loanPayment->payment_type = 2;
        $loanPayment->branch_id = auth()->user()->branch_id;
        $loanPayment->account_id = $request->account_id;
        $loanPayment->user_id = auth()->user()->id;
        $loanPayment->paid_amount = $request->paying_amount;
        $loanPayment->payment_method_id = $request->payment_method_id;
        $loanPayment->date = $request->date;
        $loanPayment->report_date = date('Y-m-d', strtotime($request->date));
        $loanPayment->save();

        $this->accountUtil->addAccountLedger(
            voucher_type_id: 15,
            date: $request->date,
            account_id: $request->account_id,
            trans_id: $loanPayment->id,
            amount: $request->paying_amount,
            balance_type: 'debit'
        );

        $dueLoans = Loan::where('type', 2)->where('loan_company_id', $company_id)->where('due', '>', 0)->get();

        foreach ($dueLoans as $dueLoan) {

            if ($dueLoan->due > $request->paying_amount) {

                if ($request->paying_amount > 0) {

                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $request->paying_amount, 2);
                    $request->paying_amount -= $request->paying_amount;
                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                } else {

                    break;
                }
            } elseif ($dueLoan->due == $request->paying_amount) {

                if ($request->paying_amount > 0) {

                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $request->paying_amount, 2);
                    $request->paying_amount -= $request->paying_amount;
                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                } else {

                    break;
                }
            } elseif ($dueLoan->due < $request->paying_amount) {

                if ($request->paying_amount > 0) {

                    $this->addLoanPaymentDistribution($loanPayment->id, $dueLoan->id, $dueLoan->due, 2);
                    $request->paying_amount -= $dueLoan->due;
                    $this->loanUtil->loanAmountAdjustment($dueLoan);
                } else {

                    break;
                }
            }
        }

        $this->loanUtil->adjustCompanyLoanLiabilityAmount($company_id);
        return response()->json('Get Loan due paid Successfully');
    }

    public function paymentList($company_id)
    {
        $company = DB::table('loan_companies')->where('id', $company_id)->first();

        $loan_payments = DB::table('loan_payments')
            ->leftJoin('accounts', 'loan_payments.account_id', 'accounts.id')
            ->leftJoin('payment_methods', 'loan_payments.payment_method_id', 'payment_methods.id')
            ->select('loan_payments.*', 'accounts.name as ac_name', 'accounts.account_number as ac_no', 'payment_methods.name as payment_method')
            ->where('loan_payments.company_id', $company_id)
            ->orderBy('loan_payments.report_date', 'desc')->get();
        return view('accounting.loans.ajax_view.payment_list', compact('company', 'loan_payments'));
    }

    public function delete($payment_id)
    {
        $deleteLoanPayment = LoanPayment::with(['loan_payment_distributions', 'loan_payment_distributions.loan'])->where('id', $payment_id)->first();
        $storedPaymentType = $deleteLoanPayment->payment_type;
        $storedCompanyId = $deleteLoanPayment->company_id;
        $storedAccountId = $deleteLoanPayment->account_id;
        $storedPaymentDistributions = $deleteLoanPayment->loan_payment_distributions;
        $deleteLoanPayment->delete();

        foreach ($storedPaymentDistributions as $storedPaymentDistribution) {

            $this->loanUtil->loanAmountAdjustment($storedPaymentDistribution->loan);
        }

        if ($storedPaymentType == 1) {

            $this->loanUtil->adjustCompanyLoanAdvanceAmount($storedCompanyId);
        } else {

            $this->loanUtil->adjustCompanyLoanLiabilityAmount($storedCompanyId);
        }

        if ($storedAccountId) {
            
            $this->accountUtil->adjustAccountBalance('debit', $storedAccountId);
        }

        return response()->json('Loan payment deleted Successfully');
    }

    private function addLoanPaymentDistribution($loanPaymentId, $loanId, $amount, $type)
    {
        $addLoanPaymentDistribution = new LoanPaymentDistribution();
        $addLoanPaymentDistribution->loan_payment_id = $loanPaymentId;
        $addLoanPaymentDistribution->loan_id = $loanId;
        $addLoanPaymentDistribution->paid_amount = $amount;
        $addLoanPaymentDistribution->payment_type = $type;
        $addLoanPaymentDistribution->save();
    }
}
