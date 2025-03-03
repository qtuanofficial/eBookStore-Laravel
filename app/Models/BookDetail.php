<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'book_detail';

    protected $fillable = [
        'book_id',
        'description',
        'summary',
        'isbn',
        'publisher',
        'publication_date',
        'pages',
        'file_url'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
