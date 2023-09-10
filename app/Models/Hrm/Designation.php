<?php

namespace App\Models\Hrm;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'hrm_designations';
    protected $fillable = ['designation_name','description'];
}
