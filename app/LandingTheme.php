<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LandingTheme extends Model
{
    use HasFactory;

    protected $table = 'landing_themes';

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'image',
    ];

    // Auto-generate slug
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

    // A theme belongs to a category
    public function category()
    {
        return $this->belongsTo(LandingCategory::class, 'category_id');
    }

    // A theme has many pages
    public function pages()
    {
        return $this->hasMany(LandingPage::class, 'theme_id');
    }

    public function imageFile()
    {
        return $this->belongsTo(Media::class, 'image');
    }
}
