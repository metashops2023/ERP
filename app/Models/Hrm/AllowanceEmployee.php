<?php

namespace App\Models\Hrm;

use App\Models\AdminAndUser;
use App\Models\Hrm\Allowance;
use Illuminate\Database\Eloquent\Model;

class AllowanceEmployee extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];
    public function employee()
    {
        return $this->belongsTo(AdminAndUser::class, 'user_id');
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class, 'allowance_id');
    }
}
