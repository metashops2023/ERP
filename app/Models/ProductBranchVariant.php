<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class ProductBranchVariant extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function product_branch()
    {
        return $this->belongsTo(ProductBranch::class, 'product_branch_id');
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
