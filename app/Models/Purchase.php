<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\AdminAndUser;
use App\Models\PurchaseReturn;
use App\Models\SupplierLedger;
use App\Models\PurchaseProduct;
use App\Models\PurchaseOrderProduct;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = 'purchases';

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id')->select(['id', 'warehouse_name', 'warehouse_code', 'phone', 'address']);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->select(['id', 'name', 'branch_code', 'phone', 'city', 'state', 'zip_code', 'country', 'logo']);
    }

    public function purchase_products()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id');
    }

    public function purchase_order_products()
    {
        return $this->hasMany(PurchaseOrderProduct::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->select(['id','name', 'business_name', 'phone', 'email', 'address', 'prefix']);
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id');
    }

    public function purchase_return()
    {
        return $this->hasOne(PurchaseReturn::class, 'purchase_id');
    }

    public function purchase_payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }

    public function ledger()
    {
        return $this->hasOne(SupplierLedger::class);
    }
}
