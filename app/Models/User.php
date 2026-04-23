<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone'
    ];

    protected $hidden = [
        'password',
    ];

    // 1 user tạo nhiều order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // shipper giao nhiều đơn
    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'shipper_id');
    }
}
