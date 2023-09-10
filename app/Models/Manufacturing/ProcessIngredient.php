<?php

namespace App\Models\Manufacturing;

use App\Models\Unit;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Manufacturing\Process;
use Illuminate\Database\Eloquent\Model;

class ProcessIngredient extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
