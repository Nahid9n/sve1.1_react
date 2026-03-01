<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebSettings extends Model
{
    protected $fillable = [
        'website_address',
        'website_phone',
        'website_phone2',
        'website_phone3',
        'website_email',
        'website_email2',
        'website_facebook',
        'website_twitter',
        'website_instagram',
        'website_youtube',
        'website_header_logo',
        'website_footer_logo',
        'website_favicon',
        'website_copyright_text',
        'currency_sign',
        'bkash_merchant_numb',
        'fb_pixel',
        'stock_management',
        'stock_alert',
        'guest_review',
        'invoice_prefix',
        'is_demo'
    ];

    public function get_header()
    {
        return $this->hasOne(Media::class, 'id', 'website_header_logo');
    }

    public function get_favicon()
    {
        return $this->hasOne(Media::class, 'id', 'website_favicon');
    }

    public function get_footer()
    {
        return $this->hasOne(Media::class, 'id', 'website_footer_logo');
    }
    protected static function booted()
    {
        static::saved(function () {
            cache()->forget('web_settings');
        });

        static::deleted(function () {
            cache()->forget('web_settings');
        });
    }
}
