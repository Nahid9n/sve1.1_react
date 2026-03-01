<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionalBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'promo_banner_1_url',
        'promo_banner_file_1_url',
        'promo_banner_1_status',
        'promo_banner_2_url',
        'promo_banner_file_2_url',
        'promo_banner_2_status',
    ];
}
