<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'description'
    ];

    // Liên kết với category cha
    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // SubCategory có nhiều sách
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
