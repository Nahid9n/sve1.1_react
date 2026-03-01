<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerSection extends Model
{
    protected $fillable = [
        'theme_id',
        'section',
        'max_items',
        'status',
    ];
    public function banners()
    {
        return $this->hasMany(Banner::class)->orderBy('order');
    }

    protected static function booted()
    {
        static::saved(function ($section) {
            cache()->forget("theme_{$section->theme_id}_promotion_banner");
        });

        static::deleted(function ($section) {
            cache()->forget("theme_{$section->theme_id}_promotion_banner");
        });
    }
}
