<?php

namespace App\Models\Essential;
use Illuminate\Database\Eloquent\Model;

class WorkspaceTask extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
}
