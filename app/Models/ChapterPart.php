<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'story',
        'image_description',
        'image_url',
        'audio',
        'order',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
