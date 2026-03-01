<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageSetting extends Model
{
    protected $fillable = [
        'theme_id',
        'section',
        'content',
        'status'
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
