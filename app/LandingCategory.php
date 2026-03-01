<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LandingCategory extends Model
{
    use HasFactory;

    protected $table = 'landing_categories';

    protected $fillable = [
        'title',
        'slug',
        'status',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->title);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('title')) {
                $category->slug = Str::slug($category->title);
            }
        });
    }

    // A category has many themes
    public function themes()
    {
        return $this->hasMany(LandingTheme::class, 'category_id');
    }
}
