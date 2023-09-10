<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leavetype extends Model
{
    use HasFactory;
     protected $table = 'hrm_leavetypes';

    protected $fillable = ['leave_type','max_leave_count','leave_count_interval'];
}
