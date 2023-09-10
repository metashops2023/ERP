<?php

namespace App\Models\Hrm;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = 'hrm_holidays';

    protected $fillable = ['holiday_name','start_date','end_date','shop_name','notes'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
