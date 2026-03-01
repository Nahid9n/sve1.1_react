<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'path',
        'image',
        'is_active',
        'status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'status' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ab_orders()
    {
        return $this->hasMany(AbandonedCart::class);
    }
    public function sections()
    {
        return $this->hasMany(BannerSection::class);
    }
}
