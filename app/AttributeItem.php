<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeItem extends Model
{
    // use HasFactory;
    protected $fillable = [
        'attribute_id',
        'name',
        'slug',
        'status',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
