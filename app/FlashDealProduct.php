<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashDealProduct extends Model
{
    protected $fillable = ['flash_deal_id', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function flash_deal()
    {
        return $this->belongsTo(FlashDeal::class);
    }
}
