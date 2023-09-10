<?php

namespace App\Models;

use App\Models\RolePermission;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function permission()
    {
        return $this->hasOne(RolePermission::class, 'role_id');
    }
}
