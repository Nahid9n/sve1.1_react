<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_date',
        'theme_id',
        'invoice_id',
        'ip_address',
        'device_id',
        'memo_number',
        'customer_activity',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'courier_id',
        'courier_city_id',
        'courier_zone_id',
        'payment_method',
        'shipping_method',
        'shipping_cost',
        'discount',
        'sub_total',
        'total',
        'paid',
        'due',
        'status',
        'customer_note',
        'staff_note',
        'payment_status',
        'source',
        'consignment_id',
        'courier_status',
        'courier_reason_msg',
        'courier_error_msg',
        'tracking_id',
        'coupon_code',
        'coupon_discount',
        'coupon_applied_on',
    ];

    protected $casts = [
        'customer_activity' => 'array',
        'courier_error_msg' => 'array',
    ];

    public function pathaoCity()
    {
        return $this->hasOne(PathaoCity::class, 'parent_id', 'courier_city_id');
    }

    public function redxArea()
    {
        return $this->hasOne(RedxArea::class, 'parent_id', 'courier_zone_id');
    }

    public function pathaoZone()
    {
        return $this->hasOne(PathaoZone::class, 'parent_id', 'courier_zone_id');
    }

    public function get_products()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id')->with('get_product');
    }

    public function get_transactions()
    {
        return $this->hasMany(AccountTransaction::class, 'order_id', 'id');
    }

    public function get_courier()
    {
        return $this->hasOne(Courier::class, 'id', 'courier_id');
    }

    // city and zone
    public function get_city()
    {
        return $this->hasOne(PathaoCity::class, 'id', 'courier_city_id');
    }

    public function get_zone()
    {
        return $this->hasOne(PathaoZone::class, 'id', 'courier_zone_id');
    }

    public function get_assigned()
    {
        return $this->hasOne(OrderAssign::class, 'order_id', 'id');
    }
    // order notes
    // public function get_notes()
    // {
    //     return $this->hasOne(OrderNote::class, 'order_id', 'id')->latestOfMany('id');
    // }

    // staff note latest one only
    // staff note latest one only
    public function get_staff_notes()
    {
        return $this->hasMany(OrderNote::class, 'order_id', 'id')->whereIn('user_type', ['super_admin', 'manager', 'employee']);
    }

    // customer notes
    public function get_customer_notes()
    {
        return $this->hasMany(OrderNote::class, 'order_id', 'id')->where('user_type', 'customer')->latest();
    }

    // order activity
    public function get_activities()
    {
        return $this->hasMany(OrderActivity::class, 'order_id', 'id')->latest();
    }

    // notes
    public function get_notes()
    {
        return $this->hasMany(OrderNote::class, 'order_id', 'id')->latest();
    }
}
