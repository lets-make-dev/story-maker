<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'image_description',
        'image_url',
        'order',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapters::class);
    }
}
