<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $table = 'hrm_shifts';
    protected $fillable = ['shift_name','shift_type','start_time','endtime','holiday'];
}
