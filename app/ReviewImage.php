<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    public $timestamps = false;

    protected $fillable = ['review_id', 'file_url'];
}
