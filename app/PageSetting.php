<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    protected $fillable = [
        'about_us',
        'delivery_policy',
        'return_policy',
        'how_to_order',
        'privacy_policy',
        'terms_condition',
        'why_us',
        'contact_us',
    ];

    protected static function booted()
    {
        static::saved(function () {
            cache()->forget('page_settings');
        });

        static::deleted(function () {
            cache()->forget('page_settings');
        });
    }
}
