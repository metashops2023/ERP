<?php

namespace App\Models;

use App\Models\Tax;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Warranty;
use App\Models\SaleProduct;
use App\Models\ComboProduct;
use App\Models\ProductImage;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\PurchaseProduct;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransferStockToWarehouseProduct;

class Product extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function ComboProducts()
    {
        return $this->hasMany(ComboProduct::class, 'product_id');
    }

    public function product_variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function product_purchased_variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id')->where('is_purchased', 1);
    }

    public function product_branches()
    {
        return $this->hasMany(ProductBranch::class);
    }

    public function product_warehouses()
    {
        return $this->hasMany(ProductWarehouse::class);
    }

    public function purchase_products()
    {
        return $this->hasMany(PurchaseProduct::class, 'product_id');
    }

    public function sale_products()
    {
        return $this->hasMany(SaleProduct::class, 'product_id');
    }

    public function order_products()
    {
        return $this->hasMany(PurchaseProduct::class);
    }

    public function transfer_to_branch_products()
    {
        return $this->hasMany(TransferStockToBranchProduct::class);
    }

    public function transfer_to_warehouse_products()
    {
        return $this->hasMany(TransferStockToWarehouseProduct::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'name']);
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'id')->select(['id', 'name']);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id')->select(['id', 'tax_name', 'tax_percent']);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id')->select('id', 'name', 'code_name');
    }

    public function warranty()
    {
        return $this->belongsTo(Warranty::class, 'warranty_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->select(['id', 'name']);
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function updateProductCost()
    {

        $settings = DB::table('general_settings')->select('business')->first();

        $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];

        if ($stockAccountingMethod == 1) {
            $ordering = 'asc';
        }else {
            $ordering = 'desc';
        }

        return $this->hasOne(PurchaseProduct::class)->where('left_qty', '>', '0')
            ->orderBy('created_at', $ordering)->select('product_id', 'net_unit_cost');
    }

    public function stock_limit()
    {
        return $this->hasOne(ProductBranch::class)->where('branch_id', auth()->user()->branch_id)
        ->select('id', 'branch_id', 'product_id', 'product_quantity');
    }
}
