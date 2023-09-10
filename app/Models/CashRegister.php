<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\CashCounter;
use App\Models\AdminAndUser;
use App\Models\CashRegisterTransaction;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];

    public function cash_register_transactions()
    {
        return $this->hasMany(CashRegisterTransaction::class);
    }

    public function cash_counter()
    {
        return $this->belongsTo(CashCounter::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id');
    }
}
