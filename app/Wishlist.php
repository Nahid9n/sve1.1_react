<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'product_id', 'sku', 'attributes', 'quantity'];

    public function get_product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->with('get_thumb');
    }

    public function get_user()
    {
        return $this->belongsTo(User::class);
    }
}
