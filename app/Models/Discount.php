<?php

namespace App\Models;

use App\Models\DiscountProduct;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function discountProducts()
    {
        return $this->hasMany(DiscountProduct::class);
    }
}
