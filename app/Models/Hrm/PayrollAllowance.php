<?php

namespace App\Models\Hrm;


use Illuminate\Database\Eloquent\Model;

class PayrollAllowance extends Model
{
    protected $table = 'hrm_payroll_allowances';
    protected $guarded = [];
    protected $hidden = ['updated_at'];
}
