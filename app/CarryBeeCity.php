<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarryBeeCity extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
    ];

    public function zones()
    {
        return $this->hasMany(CarryBeeZone::class, 'carry_bee_city_id', 'id');
    }
}
