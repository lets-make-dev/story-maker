<?php

namespace App\Observers;

use App\Actions\ChapterUpdated\GenerateStoryParts;
use App\Actions\ImageGeneration\GenerateStoryImageDescriptions;
use App\Actions\TextToSpeech\GenerateStoryAudioForAllChapterParts;
use App\Models\Chapter;
use Illuminate\Support\Facades\Bus;

class ChapterOberserver
{
    /**
     * Handle the Chapter "created" event.
     */
    public function created(Chapter $chapter): void
    {
        //
    }

    /**
     * Handle the Chapter "updated" event.
     */
    public function updated(Chapter $chapter): void
    {
        if ($chapter->story !== null && $chapter->isDirty('story')) {

            Bus::chain([
                GenerateStoryParts::makeJob($chapter),
                GenerateStoryAudioForAllChapterParts::makeJob($chapter),
                GenerateStoryImageDescriptions::makeJob($chapter),
            ])->dispatch();
        }
    }

    /**
     * Handle the Chapter "deleted" event.
     */
    public function deleted(Chapter $chapter): void
    {
        //
    }

    /**
     * Handle the Chapter "restored" event.
     */
    public function restored(Chapter $chapter): void
    {
        //
    }

    /**
     * Handle the Chapter "force deleted" event.
     */
    public function forceDeleted(Chapter $chapter): void
    {
        //
    }
}
