<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'story',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function chapterParts()
    {
        return $this->hasMany(ChapterPart::class);
    }

    public function filamentResource() {
        return \App\Filament\Resources\BookResource\RelationManagers\ChaptersRelationManager::class;
    }
}
