<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Branch;
use App\Models\Hrm\AllowanceEmployee;
use App\Models\Hrm\Attendance;
use App\Models\Hrm\Department;
use App\Models\Hrm\Designation;
use App\Models\RolePermission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Database\Eloquent\Model;

class AdminAndUser extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permission()
    {
        return $this->belongsTo(RolePermission::class, 'role_permission_id');
    }

    public function employee_allowances()
    {
        return $this->belongsTo(AllowanceEmployee::class, 'user_id');
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }
}
