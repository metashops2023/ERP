<?php

namespace App\Models;

use App\Models\PurchaseReturn;
use App\Models\SupplierPayment;
use App\Models\SupplierProduct;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];

    public function supplier_products()
    {
        return $this->hasMany(SupplierProduct::class)->where('label_qty' , '>', 0);
    }

    public function purchase_returns()
    {
        return $this->hasMany(PurchaseReturn::class, 'supplier_id');
    }

    public function supplier_payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }


}
