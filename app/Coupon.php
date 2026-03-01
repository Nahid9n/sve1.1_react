<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'amount',
        'max_discount',
        'min_purchase',
        'apply_on',
        'product_ids',
        // 'exclude_product_ids',
        'payment_method',
        'usage_limit',
        'used_count',
        'per_user_limit',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'exclude_product_ids' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')
            ->withPivot('used_count')
            ->withTimestamps();
    }

    public function isActive()
    {
        return $this->status &&
            (! $this->start_date || now()->gte($this->start_date)) &&
            (! $this->end_date || now()->lte($this->end_date));
    }
}
