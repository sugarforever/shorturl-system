<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'long_url',
        'token',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function view()
    {
        return $this->hasOne(ShortUrlView::class);
    }
}
