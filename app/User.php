<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'status',
        'otp_expires_at',
        'otp_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function get_orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    // get_coupon count
    public function get_coupon_count()
    {
        return $this->hasOne(CouponUser::class, 'user_id', 'id');
    }
}
