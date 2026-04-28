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

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Nhân viên tạo đơn
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bàn
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Danh sách món
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Thanh toán
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Giao hàng (nếu có)
    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    // ❗ Quan trọng: Lý do huỷ
    public function cancelled()
    {
        return $this->hasOne(CancelledOrder::class);
    }
}
