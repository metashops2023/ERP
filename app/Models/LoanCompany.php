<?php

namespace App\Models;

use App\Models\Loan;
use App\Models\LoanPayment;
use Illuminate\Database\Eloquent\Model;

class LoanCompany extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function loanPayments()
    {
        return $this->hasMany(LoanPayment::class, 'company_id');
    }
}
