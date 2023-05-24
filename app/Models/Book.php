<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'cover_image',
        'summary',
        'image_style',
        'spoken_audio_intro',
        'spoken_audio_outro',
        'audio_intro_file',
        'audio_outro_file',
    ];

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function chapterParts()
    {
        return $this->hasMany(ChapterPart::class);
    }

    public function filamentResource() {
        return \App\Filament\Resources\BookResource::class;
    }
}
