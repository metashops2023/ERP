<?php

namespace App\Models;

use App\Models\MoneyReceipt;
use App\Models\CustomerGroup;
use App\Models\CustomerLedger;
use App\Models\CustomerPayment;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];
    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function ledgers()
    {
        return $this->hasMany(CustomerLedger::class);
    }

    public function sale_returns()
    {
        return $this->hasMany(SaleReturn::class, 'customer_id');
    }

    public function receipts()
    {
        return $this->hasMany(MoneyReceipt::class)->where('branch_id', auth()->user()->branch_id);
    }

    public function customer_payments()
    {
        return $this->hasMany(CustomerPayment::class);
    }
}
