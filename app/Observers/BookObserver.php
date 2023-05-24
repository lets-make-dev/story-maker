<?php

namespace App\Observers;

use App\Actions\TextToSpeech\GenerateTextToSpeech;
use App\Models\Book;

class BookObserver
{
    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {

        if ($book->spoken_audio_intro !== null && $book->isDirty('spoken_audio_intro')) {
            GenerateTextToSpeech::dispatch($book, 'audio_intro_file', $book->spoken_audio_intro);
        }

        if ($book->spoken_audio_outro !== null && $book->isDirty('spoken_audio_outro')) {
            GenerateTextToSpeech::dispatch($book, 'audio_outro_file', $book->spoken_audio_outro);
        }
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        //
    }
}
