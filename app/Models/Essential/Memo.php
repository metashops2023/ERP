<?php

namespace App\Models\Essential;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function memo_users()
    {
        return $this->hasMany(MemoUser::class);
    }
}
