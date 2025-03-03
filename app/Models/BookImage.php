<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_id',
        'image_url',
        'alt_text'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
