<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
    ];
}
