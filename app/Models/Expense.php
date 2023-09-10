<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\AdminAndUser;
use App\Models\ExpenseDescription;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function expense_descriptions()
    {
        return $this->hasMany(ExpenseDescription::class, 'expense_id');
    }

    public function expense_payments()
    {
        return $this->hasMany(ExpensePayment::class, 'expense_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id');
    }
}
