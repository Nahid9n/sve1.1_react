<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'purchase_price',
        'price',
        'attributes',
        'product_sku',
        'discount',
    ];

    public function get_product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->with('get_thumb', 'get_feature_image');
    }

    // order
    public function get_order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
