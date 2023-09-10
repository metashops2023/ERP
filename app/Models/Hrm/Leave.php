<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdminAndUser;
use App\Models\Hrm\Leavetype;
class Leave extends Model
{
    use HasFactory;
    protected $table = 'hrm_leaves';
    protected $fillable = ['reference_number','employee_id','leave_id','start_date','end_date','reason','status'];

     public function admin_and_user()
    {
        return $this->belongsTo(AdminAndUser::class,'employee_id');
    }

    public function leave_type()
    {
        return $this->belongsTo(Leavetype::class,'leave_id');
    }

}
