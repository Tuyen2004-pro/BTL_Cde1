<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'category_id',
        'status',
        'description'
    ];

    // thuộc 1 category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 1 sản phẩm có nhiều order_item
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
