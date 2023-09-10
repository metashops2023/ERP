<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderProduct extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function receives()
    {
        return $this->hasMany(PurchaseOrderProductReceive::class, 'order_product_id');
    }
}
