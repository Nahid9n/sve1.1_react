<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'category_name',
        'status',
        'file_url',
        'slug',
        'theme_id',
        'position',
        'is_show_home',
        'extra_fields',
    ];

    protected $casts = [
        'extra_fields' => 'array',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function get_products()
    {
        return $this->belongsToMany(Product::class, 'category_products');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id'); // query builder
    }
}
