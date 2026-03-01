<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'account_type', 'bkash_no', 'nagad_no', 'rocket_no', 'bank_name', 'branch_name', 'routing_no', 'bank_account_no', 'bank_account_name', 'status', 'is_default', 'balance',
    ];

    public function get_account_transactions()
    {
        return $this->hasMany(AccountTransaction::class, 'account_id', 'id');
    }
}
