<?php

namespace App\Models;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
