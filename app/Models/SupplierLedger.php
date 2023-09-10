<?php

namespace App\Models;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\PurchasePayment;
use App\Models\SupplierPayment;
use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function purchase_payment()
    {
        return $this->belongsTo(PurchasePayment::class, 'purchase_payment_id');
    }

    public function supplier_payment()
    {
        return $this->belongsTo(SupplierPayment::class, 'supplier_payment_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
