<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    public function get_expenses()
    {
        return $this->hasMany(Expense::class, 'category_id', 'id');
    }
}
