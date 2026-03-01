<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'date',
        'status',
        'memo_no',
        'remarks',
        'subtotal',
        'discount',
        'total',
        'account_id',
        'paid_amount',
        'due_amount',
        'previous_due',
        'purchase_date',
        'note',
    ];

    public function get_supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function get_purchase_items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id', 'id')->with('get_product');
    }
}
