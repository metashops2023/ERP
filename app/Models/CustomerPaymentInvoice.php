<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\SaleReturn;
use Illuminate\Database\Eloquent\Model;

class CustomerPaymentInvoice extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function sale_return()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }
}
