<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarryBeeZone extends Model
{
    protected $fillable = [
        'parent_id',
        'carry_bee_city_id',
        'name',
    ];

    public function city()
    {
        return $this->belongsTo(CarryBeeCity::class, 'carry_bee_city_id', 'id');
    }
}
