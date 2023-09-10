<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseBranch extends Model
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->select('id', 'name', 'branch_code');
    }
}
