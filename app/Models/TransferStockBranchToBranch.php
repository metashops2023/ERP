<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransferStockBranchToBranchProducts;

class TransferStockBranchToBranch extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function sender_branch()
    {
        return $this->belongsTo(Branch::class, 'sender_branch_id');
    }

    public function sender_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'sender_warehouse_id');
    }

    public function receiver_branch()
    {
        return $this->belongsTo(Branch::class, 'receiver_branch_id');
    }

    public function receiver_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'receiver_warehouse_id');
    }

    public function transfer_products()
    {
        return $this->hasMany(TransferStockBranchToBranchProducts::class, 'id');
    }

    public function expense()
    {
        return $this->hasOne(Expense::class, 'transfer_branch_to_branch_id');
    }
}
