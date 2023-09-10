<?php

namespace App\Utils\Hrm;

use Illuminate\Support\Facades\DB;

class PayrollUtil
{
    public function adjustPayrollAmounts($payroll)
    {
        $totalPayrollPaid = DB::table('hrm_payroll_payments')
            ->where('hrm_payroll_payments.payroll_id', $payroll->id)
            ->select(DB::raw('sum(paid) as total_paid'))
            ->groupBy('hrm_payroll_payments.payroll_id')
            ->get();

        $due = $payroll->gross_amount - $totalPayrollPaid->sum('total_paid');

        $payroll->paid = $totalPayrollPaid->sum('total_paid');
        $payroll->due = $due;
        $payroll->save();
    }
}
