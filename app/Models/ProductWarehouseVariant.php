<?php

namespace App\Models;


use App\Models\ProductWarehouse;
use Illuminate\Database\Eloquent\Model;

class ProductWarehouseVariant extends Model
{
    public function product_warehouse()
    {
        return $this->belongsTo(ProductWarehouse::class, 'product_warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
