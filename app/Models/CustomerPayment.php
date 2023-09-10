<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Account;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\CustomerPaymentInvoice;
use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function customer_payment_invoices()
    {
        return $this->hasMany(CustomerPaymentInvoice::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
