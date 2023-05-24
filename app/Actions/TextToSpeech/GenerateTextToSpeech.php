<?php

namespace App\Actions\TextToSpeech;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\ChapterPart;
use Aws\Polly\PollyClient;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateTextToSpeech
{
    use AsAction;

    public string $commandSignature = 'story:tts:generate {model?} {modelId?} {fieldKey?} {text?} {rate=fast}';

    public function handle($model, $fieldKey, string $text, $rate = "fast")
    {
        $isBook = $model instanceof Book;
        if ($isBook) {
            $book = $model;
        } else {
            $book = $model->book;
        }

        $ssml = "<speak><prosody rate=\"$rate\">$text</prosody></speak>";

        $pollyClient = new PollyClient([
            'region' => 'us-west-2',  // Set your AWS region here
            'version' => 'latest',
            'credentials' => [
                'key' => config('services.ses.key'),
                'secret' => config('services.ses.secret'),
            ]
        ]);

        $result = $pollyClient->synthesizeSpeech([
            'OutputFormat' => 'mp3',  // You can change this to the format you prefer
            'Text' => $ssml,
            'TextType' => 'ssml',
            'VoiceId' => 'Kevin',  // Here you can set the voice you want to use
            'Engine' => 'neural'
        ]);

        $reflection = new \ReflectionClass($model);
        $shortClassName = $reflection->getShortName(); // Returns 'Book'
        $lowercaseClassName = strtolower($shortClassName); // Converts 'Book' to


        $audioStream = $result->get('AudioStream');

        $folderPath = 'audio/' . Str::slug($book->title) . '_' . $book->id . '/';
        $filename = $folderPath . $lowercaseClassName . '_' . $fieldKey . '.mp3';

        Storage::put($filename, $audioStream);

        $model->$fieldKey = $filename;
        $model->save();

    }

    public function asCommand(Command $command): void
    {

        $modelClass = $command->argument('model') ?? $command->prompt('Model');
        $modelId = $command->argument('modelId') ?? $command->prompt('Model ID');
        $modelClass = 'App\\Models\\' . $modelClass;
        $model = $modelClass::findOrFail($modelId);

        $text = $command->argument('text') ?? $command->prompt('Text to Transcribe');
        $fieldKey = $command->argument('fieldKey') ?? $command->prompt('Field Key');


        $this->handle($model, $fieldKey, $text);
    }

    public function asJob($model, string $fieldKey, $text, $speed = "fast"): void
    {
        ray("🚀", $text);
        $this->handle($model, $fieldKey, $text, $speed);
    }

}
