<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'cover',
        'sub_category_id',
        'price'
    ];

    // Mỗi sách thuộc về 1 sub_category
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // Quan hệ 1-1 với book_detail
    public function detail()
    {
        return $this->hasOne(BookDetail::class);
    }

    // Một sách có nhiều hình ảnh bổ sung
    public function images()
    {
        return $this->hasMany(BookImage::class);
    }

    // Sách có nhiều đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Sách xuất hiện trong nhiều mục giỏ hàng
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Sách xuất hiện trong nhiều đơn hàng
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Sách nằm trong danh sách yêu thích của người dùng
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
