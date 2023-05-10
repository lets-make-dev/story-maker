<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapters extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
