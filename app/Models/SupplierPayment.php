<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Account;
use App\Models\Supplier;
use App\Models\SupplierPaymentInvoice;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function supplier_payment_invoices()
    {
        return $this->hasMany(SupplierPaymentInvoice::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
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
