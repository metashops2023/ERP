<?php

namespace App\Models;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function Subcategories()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function parent_category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')->where('number_of_sale', '>', 0);
    }
}
