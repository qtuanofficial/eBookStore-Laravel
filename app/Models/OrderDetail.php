<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Một đơn hàng có nhiều mục
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Một đơn hàng có một thông tin thanh toán
    public function payment()
    {
        return $this->hasOne(PaymentDetail::class, 'order_id');
    }
}
