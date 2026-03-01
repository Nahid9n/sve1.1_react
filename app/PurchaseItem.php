<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'sku',
        'product_quantity',
        'purchase_cost',
        'regular_price',
        'sell_price',
        'total',
    ];

    public function get_purchase()
    {
        return $this->hasOne(Purchase::class, 'id', 'purchase_id');
    }

    public function get_product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
