<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class MoneyReceipt extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}

