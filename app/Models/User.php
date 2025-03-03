<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // nếu dùng Auth
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'avatar',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'birth_of_date',
        'phone_number'
    ];

    // Mỗi user có nhiều địa chỉ
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // User có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // User có nhiều sách trong danh sách yêu thích
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Giả sử mỗi người dùng duy nhất chỉ có 1 giỏ hàng đang hoạt động
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // User có thể có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Quan hệ many-to-many với bảng roles qua bảng pivot user_roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
}
