<?php

namespace App\Models\Essential;
use App\Models\AdminAndUser;
use Illuminate\Database\Eloquent\Model;

class TodoUsers extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(AdminAndUser::class);
    }
}
