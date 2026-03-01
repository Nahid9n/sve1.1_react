<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'role',
        'status',
        'is_order_assign',
        'token',
        'image',
        'theme_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function get_orders()
    // {
    //     return $this->hasMany(OrderAssign::class,'employee_id','id')->with('get_order');
    // }
    public function get_order_assign()
    {
        return $this->hasMany(OrderAssign::class, 'employee_id');
    }
    public function get_theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }
}
