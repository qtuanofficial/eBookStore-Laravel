<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_item';

    protected $fillable = [
        'order_id',
        'book_id',
        'quantity',
        'price'
    ];

    public function order()
    {
        return $this->belongsTo(OrderDetail::class, 'order_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
