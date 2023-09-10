<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Purchase;
use App\Models\Warehouse;
use App\Models\PurchaseReturnProduct;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function purchase_return_products()
    {
        return $this->hasMany(PurchaseReturnProduct::class);
    }
}
