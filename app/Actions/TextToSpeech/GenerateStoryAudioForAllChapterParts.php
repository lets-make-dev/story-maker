<?php

namespace App\Actions\TextToSpeech;

use App\Models\Chapter;
use App\Models\ChapterPart;
use Aws\Polly\PollyClient;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateStoryAudioForAllChapterParts
{
    use AsAction;

    public string $commandSignature = 'story:tts:generate-chapter {chapterId?}';

    public function handle(Chapter $chapter)
    {

        $storyParts = $this->storyParts($chapter);

        foreach ($storyParts as $storyPart) {
            GenerateStoryAudioForChapterPart::dispatch($chapter, $storyPart);
        }

    }

    public function asCommand(Command $command): void
    {
        $chapterId = $command->ask('Chapter Id');
        $chapter = Chapter::findOrFail($chapterId);

        $this->handle($chapter);
    }

    public function asJob(Chapter $chapter): void
    {
        $this->handle($chapter);
    }

    private function storyParts(Chapter $chapter): Collection
    {
        $chapterParts = ChapterPart::select('id', 'story')
            ->where('chapter_id', $chapter->id)
            ->whereNull('audio_file')
            ->orderBy('id')
            ->get();

        return $chapterParts;
    }
}
