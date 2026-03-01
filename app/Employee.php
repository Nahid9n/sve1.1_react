<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
    ];

    public function get_assigned_orders()
    {
        return $this->hasMany(OrderAssign::class, 'employee_id', 'id');
    }
}
