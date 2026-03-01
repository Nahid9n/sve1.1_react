<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    // use HasFactory;
    protected $fillable = [
        'banner_section_id',
        'image',
        'link',
        'order',
        'status',
    ];
    public function section()
    {
        return $this->belongsTo(BannerSection::class);
    }
}
