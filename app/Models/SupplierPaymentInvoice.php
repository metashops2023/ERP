<?php

namespace App\Models;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;

class SupplierPaymentInvoice extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function supplier_return_invoice()
    {
        return $this->hasMany(PurchaseReturn::class, 'supplier_return_id');
    }
}
