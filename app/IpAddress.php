<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'total_order',
        'status',
    ];
}
