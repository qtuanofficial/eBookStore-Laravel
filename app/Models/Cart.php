<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cart có nhiều mục
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
}
