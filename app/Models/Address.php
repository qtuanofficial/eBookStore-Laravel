<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'address_line_1',
        'address_line_2',
        'country',
        'city',
        'postal_code',
        'landmark',
        'phone_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
