<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderNote extends Model
{
    protected $fillable = ['order_id', 'note', 'user_id', 'user_type'];

    public function get_order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
