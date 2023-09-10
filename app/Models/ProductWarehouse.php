<?php

namespace App\Models;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;


class ProductWarehouse extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id')->select('id', 'branch_id','warehouse_name', 'warehouse_code');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function product_warehouse_variants()
    {
        return $this->hasMany(ProductWarehouseVariant::class, 'product_warehouse_id');
    }
}
