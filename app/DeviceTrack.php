<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceTrack extends Model
{
    protected $fillable = [
        'device_id',
        'user_id',
        'device_name',
        'device_type',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
