<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductBranchVariant;
use Illuminate\Database\Eloquent\Model;

class ProductBranch extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_branch_variants()
    {
        return $this->hasMany(ProductBranchVariant::class, 'product_branch_id');
    }
}
