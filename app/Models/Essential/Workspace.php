<?php

namespace App\Models\Essential;

use App\Models\AdminAndUser;
use Illuminate\Database\Eloquent\Model;
use App\Models\Essential\WorkspaceUsers;

class Workspace extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function ws_users()
    {
        return $this->hasMany(WorkspaceUsers::class);
    }

    public function admin()
    {
        return $this->belongsTo(AdminAndUser::class, 'admin_id')->select('id', 'prefix', 'name', 'last_name');
    }
}
