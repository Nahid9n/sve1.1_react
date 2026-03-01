<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LandingPage extends Model
{
    use HasFactory;

    protected $table = 'landing_pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'style',
        'theme_id',
        'product_ids',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($theme) {
            if (empty($theme->slug)) {
                $theme->slug = Str::slug($theme->title);
            }
        });

        static::updating(function ($theme) {
            if ($theme->isDirty('title')) {
                $theme->slug = Str::slug($theme->title);
            }
        });
    }

    // A page belongs to a theme
    public function theme()
    {
        return $this->belongsTo(LandingTheme::class, 'theme_id');
    }

    // Convert product_ids to array automatically
    protected $casts = [
        'product_ids' => 'array',
    ];
}
