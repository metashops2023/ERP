<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseSaleProductChain;

class SaleProduct extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function purchaseSaleProductChains()
    {
        return $this->hasMany(PurchaseSaleProductChain::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'stock_branch_id')->select('id', 'name', 'branch_code');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'stock_warehouse_id')->select('id', 'warehouse_name', 'warehouse_code');
    }
}
