<?php

namespace App\Models\Hrm;

use App\Models\Hrm\AllowanceEmployee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allowance extends Model
{
    use HasFactory;
    protected $table = 'hrm_allowance';

    protected $fillable = ['description','type','employee_id','amount_type','amount','applicable_date', 'is_delete_in_update'];

    public function allowance_employees()
    {
        return $this->hasMany(AllowanceEmployee::class, 'allowance_id');
    }

}
