<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    // use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'status',
        'is_image',
    ];

    public function items()
    {
        return $this->hasMany(AttributeItem::class);
    }
}
