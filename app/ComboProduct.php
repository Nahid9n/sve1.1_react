<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComboProduct extends Model
{
    protected $fillable = [
        'combo_product_id',
        'product_id',
        'purchase_price',
        'regular_price',
        'sale_price',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function comboParent()
    {
        return $this->belongsTo(Product::class, 'combo_product_id');
    }
}
