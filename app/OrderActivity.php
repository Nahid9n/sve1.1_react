<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderActivity extends Model
{
    protected $fillable = [
        'order_id',
        'user_type',
        'created_by',
        'text',
        'old_order',
        'new_order',
        'activity_type',
        'comment',
    ];

    protected $casts = [
        'old_order' => 'array',
        'new_order' => 'array',
    ];

    // New order products
    public function getNewProductDetails()
    {
        $products = $this->new_order['products'] ?? [];

        return $products;
    }

    // Old order products
    public function getOldProductDetails()
    {
        $products = $this->old_order['products'] ?? [];

        return $products;
    }
}
