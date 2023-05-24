<?php

namespace App\Observers;

use App\Actions\GenerateImage;
use App\Actions\TextToSpeech\GenerateTextToSpeech;
use App\Models\ChapterPart;

class ChapterPartOberserver
{
    /**
     * Handle the ChapterPart "created" event.
     */
    public function created(ChapterPart $chapterPart): void
    {
        // Generate an image for the ChapterPart if it has an image_description
        if ($chapterPart->image_description && $chapterPart->image_description !== '') {
            GenerateImage::dispatch($chapterPart->image_description, $chapterPart->book->image_style, $chapterPart);
        }

        // If it doesn't have a description, then generate one

    }

    /**
     * Handle the ChapterPart "updated" event.
     */
    public function updated(ChapterPart $chapterPart): void
    {
        // Generate an image for the ChapterPart if it has an image_description
        if ($chapterPart->image_description !== '' && $chapterPart->isDirty('image_description')) {
            GenerateImage::dispatch($chapterPart->image_description, $chapterPart->book->image_style, $chapterPart);
        }

        if ($chapterPart->story !== '' && $chapterPart->isDirty('story')) {
            GenerateTextToSpeech::dispatch($chapterPart, 'audio_file', $chapterPart->story, 'medium');
        }
    }

    /**
     * Handle the ChapterPart "deleted" event.
     */
    public function deleted(ChapterPart $chapterPart): void
    {
        //
    }

    /**
     * Handle the ChapterPart "restored" event.
     */
    public function restored(ChapterPart $chapterPart): void
    {
        //
    }

    /**
     * Handle the ChapterPart "force deleted" event.
     */
    public function forceDeleted(ChapterPart $chapterPart): void
    {
        //
    }
}
