<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;

class CashRegisterTransaction extends Model
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
