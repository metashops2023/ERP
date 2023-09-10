<?php

namespace App\Models;

use App\Models\PaymentMethod;
use App\Models\StockAdjustment;
use Illuminate\Database\Eloquent\Model;

class StockAdjustmentRecover extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function adjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
