<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversionApi extends Model
{
    protected $fillable = [
        'name',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected static function booted()
    {
        static::saved(function () {
            cache()->forget('conversion_api');
        });

        static::deleted(function () {
            cache()->forget('conversion_api');
        });
    }
}
