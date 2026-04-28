<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelledOrder extends Model
{
    protected $table = 'cancelled_orders';

    protected $fillable = [
        'order_id',
        'cancelled_by',
        'reason'
    ];

    // thuộc về order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // người huỷ (admin hoặc staff)
    public function user()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}
