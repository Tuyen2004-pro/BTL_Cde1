<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'status'
    ];

    // 1 bàn có nhiều order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
