<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCourier extends Model
{
    use HasFactory;

    protected $fillable = [
        'courier_name',
        'config',
        'status',
        'password',
        'username',
        'base_url',
    ];

    protected $casts = [
        'config' => 'array',
    ];
}
