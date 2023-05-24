<?php

namespace App\Actions\VideoGeneration;

use App\Models\Book;
use App\Models\ChapterPart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\Yaml\Yaml;

class GenerateVideoDataInput
{
    use AsAction;

    public string $commandSignature = 'story:video:generate-input {bookId}';

    public function handle(Book $book)
    {
        $data = [];

        $chapterParts = ChapterPart::where('book_id', $book->id)
//            ->whereNotNull('audio_file')
            ->orderBy('order')
            ->get();


        foreach ($chapterParts as $chapterPart) {
            $data[] = [
                'audio_file' => Storage::path($chapterPart->audio_file),
                'pause' => 1.2, // 1 second
                'image' => $chapterPart->image_url ?? null,
                'effect' => "fadein",
                'effect_duration' => 3, // 3 seconds
                'caption' => $chapterPart->story,
            ];

        }

        // print_r($data); // prints the data array

        // convert to YAML
        $data = Yaml::dump($data);

        echo $data;


    }

    public function asCommand(Command $command): void
    {
        $bookId = $command->argument('bookId');
        $book = Book::findOrFail($bookId);

        $this->handle($book);
    }
}
