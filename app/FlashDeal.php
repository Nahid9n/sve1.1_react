<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashDeal extends Model
{
    protected $fillable = ['title', 'start_time', 'end_time', 'status', 'discount', 'discount_type'];

    // FlashDeal model
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(FlashDealProduct::class)->with('product');
    }

    public function isActive()
    {
        $now = now();

        return $this->status == 1 &&
            $this->start_time <= $now &&
            $this->end_time >= $now;
    }
}
