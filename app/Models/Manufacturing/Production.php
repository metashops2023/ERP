<?php

namespace App\Models\Manufacturing;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductVariant;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function ingredients()
    {
        return $this->hasMany(ProductionIngredient::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function stock_branch() // Ingredient stock branch
    {
        return $this->belongsTo(Branch::class, 'stock_branch_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function stock_warehouse() // Ingredient stock warehouse
    {
        return $this->belongsTo(Warehouse::class, 'stock_warehouse_id');
    }
}
