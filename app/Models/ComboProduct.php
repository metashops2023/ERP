<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class ComboProduct extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'combo_product_id', 'id');
    } 
    
    public function product_variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }
}
