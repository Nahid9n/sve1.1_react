<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category_id',
        'amount',
        'note',
    ];

    public function get_category()
    {
        return $this->hasOne(ExpenseCategory::class, 'id', 'category_id');
    }

    public function get_transaction()
    {
        return $this->hasOne(AccountTransaction::class, 'expense_id', 'id');
    }
}
