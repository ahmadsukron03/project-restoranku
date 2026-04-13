<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'price',
        'tax',
        'total_price',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];

    // Relasi ke tabel Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke tabel Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
