<?php

namespace App\Models;
use App\Models\Branch;
use App\Models\Warehouse;
// use App\Models\AdminAndUser;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransferStockToWarehouseProduct;

class TransferStockToWarehouse extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    // public function admin()
    // {
    //     return $this->belongsTo(AdminAndUser::class, 'admin_id');
    // }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function Transfer_products()
    {
        return $this->hasMany(TransferStockToWarehouseProduct::class, 'transfer_stock_id');
    }
}
