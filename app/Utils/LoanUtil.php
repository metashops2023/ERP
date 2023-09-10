<?php
namespace App\Utils;

use App\Models\LoanCompany;
use Illuminate\Support\Facades\DB;

class LoanUtil
{
    public function adjustCompanyLoanAdvanceAmount($companyId)
    {
        $payLoan = DB::table('loans')->where('loans.loan_company_id', $companyId)
        ->where('loans.type', 1)
        ->select(
            DB::raw('sum(loan_amount) as t_amount'),
            DB::raw('sum(due) as t_due'),
            DB::raw('sum(total_receive) as t_receive'),
        )->groupBy('loans.loan_company_id')->get();

        $company = LoanCompany::where('id', $companyId)->first();
        $company->pay_loan_amount = $payLoan->sum('t_amount');
        $company->pay_loan_due = $payLoan->sum('t_due');
        $company->total_receive = $payLoan->sum('t_receive');
        $company->save();
    }

    public function adjustCompanyLoanLiabilityAmount($companyId)
    {
        $receiveLoan = DB::table('loans')->where('loans.loan_company_id', $companyId)
        ->where('loans.type', 2)
        ->select(
            DB::raw('sum(loan_amount) as t_amount'),
            DB::raw('sum(due) as t_due'),
            DB::raw('sum(total_paid) as t_paid'),
        )->groupBy('loans.loan_company_id')->get();

        $company = LoanCompany::where('id', $companyId)->first();
        $company->get_loan_amount = $receiveLoan->sum('t_amount');
        $company->get_loan_due = $receiveLoan->sum('t_due');
        $company->total_pay = $receiveLoan->sum('t_paid');
        $company->save();
    }

    public function loanAmountAdjustment($loan)
    {
        if ($loan->type == 1) {

            $loanPaymentDistributions = DB::table('loan_payment_distributions')->where('loan_id', $loan->id)
            ->where('loan_payment_distributions.payment_type', 1)
            ->select(
                DB::raw('sum(paid_amount) as t_paid'),
            )->groupBy('loan_payment_distributions.loan_id')->get();

            $total_receive = $loanPaymentDistributions->sum('t_paid');
            $total_due = $loan->loan_amount - $total_receive;
            $loan->due = $total_due;
            $loan->total_receive = $total_receive;
            $loan->save();
        } else {

            $loanPaymentDistributions = DB::table('loan_payment_distributions')->where('loan_id', $loan->id)
            ->where('loan_payment_distributions.payment_type', 2)
            ->select(
                DB::raw('sum(paid_amount) as t_paid'),
            )->groupBy('loan_payment_distributions.loan_id')->get();

            $total_paid = $loanPaymentDistributions->sum('t_paid');
            $total_due = $loan->loan_amount - $total_paid;
            $loan->due = $total_due;
            $loan->total_paid = $total_paid;
            $loan->save();
        }
    }
}
