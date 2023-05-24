<?php

namespace App\Actions\ChapterUpdated;

use App\Models\Chapter;
use App\Models\ChapterPart;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateStoryParts
{
    use AsAction;

    public string $commandSignature = 'story:chapter:split-story-parts';

    public function handle(Chapter $chapter)
    {
        // ...
        $story = $chapter->story;
        // explode by newlines
        $storyParts = explode("\n", $story);
        // remove empty lines
        $storyParts = array_filter($storyParts, fn($line) => $line !== '');

        // foreach $storyPart, explode by sentences
        $storyParts = array_map(fn($storyPart) => explode('.', $storyPart), $storyParts);

        // Add the sentence period back to each sentence
        $storyParts = array_map(fn($storyPart) => array_map(fn($sentence) => $sentence . '.', $storyPart), $storyParts);

        // flatten array
        $storyParts = array_merge(...$storyParts);

        // trim whitespace
        $storyParts = array_map(fn($storyPart) => trim($storyPart), $storyParts);

        // remove empty lines and items containing just a single "." character
        $storyParts = array_filter($storyParts, fn($line) => $line !== '' && $line !== '.');

        // re-key array
        $storyParts = array_values($storyParts);

        // remove any periods if the $storyPart is a Markdown heading
        $storyParts = array_map(fn($storyPart) => $storyPart[0] === '#' ? str_replace('.', '', $storyPart) : $storyPart, $storyParts);


        // delete all existing (and future) ChapterParts for this Chapter
        ChapterPart::where('book_id', $chapter->book_id)
            ->where('chapter_id', '>=', $chapter->id)
            ->delete();

        $partCount = ChapterPart::where('book_id', $chapter->book_id)->count();

        // create new ChapterParts
        foreach ($storyParts as $index => $storyPart) {
            $chapterPart = (new ChapterPart)->fill([
                'story' => $storyPart,
                'order' => $partCount + $index + 1,
            ]);

            $chapterPart->book()->associate($chapter->book);
            $chapterPart->chapter()->associate($chapter);
            $chapterPart->save();

        }

    }

    public function asCommand(Command $command): void
    {
        $chapter = Chapter::findOrFail($command->ask('Chapter Id'));
        $this->handle($chapter);

    }

    public function asJob(Chapter $chapter): void
    {
        $this->handle($chapter);
    }
}
