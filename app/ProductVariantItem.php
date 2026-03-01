<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'attribute_id',
        'attribute_item_id',
        'name',
        'image',
    ];

    public function attribute()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function attribute_item()
    {
        return $this->hasOne(AttributeItem::class, 'id', 'attribute_item_id');
    }

    // image
    public function item_image()
    {
        return $this->hasOne(Media::class, 'id', 'image');
    }
}
