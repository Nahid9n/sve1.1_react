<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    protected $fillable = ['account_id', 'expense_id', 'purchase_id', 'order_id', 'amount', 'transaction_type', 'purpose'];

    public function get_account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id')->withDefault();
    }

    public function get_order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id')->withDefault();
    }
}
