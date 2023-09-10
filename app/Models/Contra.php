<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

class Contra extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function senderAccount()
    {
        return $this->belongsTo(Account::class, 'sender_account_id');
    }

    public function receiverAccount()
    {
        return $this->belongsTo(Account::class, 'receiver_account_id');
    }

    public function user()
    {
        return $this->belongsTo(AdminAndUser::class, 'user_id');
    }
}
