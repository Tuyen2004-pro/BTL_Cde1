<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'shipper_id',
        'address',
        'status'
    ];

    // thuộc order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // shipper
    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipper_id');
    }
}
