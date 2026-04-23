<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'total_price',
        'status'
    ];

    // thuộc về staff
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // thuộc về bàn
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // có nhiều item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // có 1 thanh toán
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // có thể có giao hàng
    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }
}
