<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;

class LoanPaymentDistribution extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
