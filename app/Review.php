<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'rating',
        'review',
        'status',
    ];

    public function get_review_images()
    {
        return $this->hasMany(ReviewImage::class, 'review_id', 'id');
    }

    public function get_product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
