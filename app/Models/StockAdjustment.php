<?php

namespace App\Models;
use App\Models\Branch;
use App\Models\AdminAndUser;
use App\Models\StockAdjustmentProduct;
use App\Models\StockAdjustmentRecover;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->select(['id', 'name', 'branch_code', 'phone', 'city', 'state', 'zip_code', 'country']);
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id');
    }
    
    public function adjustment_products()
    {
        return $this->hasMany(StockAdjustmentProduct::class);
    }

    public function recover()
    {
        return $this->hasOne(StockAdjustmentRecover::class);
    }
}
