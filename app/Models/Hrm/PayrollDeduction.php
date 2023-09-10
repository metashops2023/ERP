<?php

namespace App\Models\Hrm;
use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
{
    protected $table = 'hrm_payroll_deductions';
    protected $guarded = [];
    protected $hidden = ['updated_at'];
}
