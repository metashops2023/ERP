<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseSaleProductChain;

class PurchaseProduct extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function purchaseSaleChains()
    {
        return $this->hasMany(PurchaseSaleProductChain::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
