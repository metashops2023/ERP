<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseReturn;
use App\Models\PurchaseProduct;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnProduct extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function purchase_return()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function purchase_product()
    {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
