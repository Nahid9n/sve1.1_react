<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'theme_id',
        'slider_image',
        'slider_url',
        'status',
        'position'
    ];

    public function get_img()
    {
        return $this->hasOne(Media::class, 'id', 'slider_image');
    }

    protected static function booted()
    {
        static::saved(function ($slider) {
            cache()->forget('sliders');
        });
    }
}
