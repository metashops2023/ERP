<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'hrm_attendances';

    protected $fillable = ['at_date','user_id','clock_in','clock_out','work_duration','clock_in_note','clock_out_note','shift_id','month','year'];

}
