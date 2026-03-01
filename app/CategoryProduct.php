<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $fillable = [
        'category_id',
        'product_id',
    ];

    public function get_product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function get_category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->with('get_products');
    }
}
