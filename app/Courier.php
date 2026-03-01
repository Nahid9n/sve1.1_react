<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $fillable = [
        'courier_name',
        'status',
    ];

    // get all orders
    public function get_orders()
    {
        return $this->hasMany(Order::class, 'courier_id', 'id');
    }
}
