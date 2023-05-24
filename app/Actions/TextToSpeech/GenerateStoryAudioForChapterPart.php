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

class GenerateStoryAudioForChapterPart
{
    use AsAction;

    public string $commandSignature = 'story:tts:generate-chapter {chapterId?}';

    public function handle(Chapter $chapter, $storyPart)
    {
        $text = $storyPart['story'] ?? $storyPart['heading'];

        $ssml = "<speak>$text</speak>";

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

        $audioStream = $result->get('AudioStream');

        $folderPath = 'audio/' . Str::slug($chapter->book->title) . '_' . $chapter->book->id . '/';

        Storage::put($folderPath . $chapter->id . '_' . $storyPart['id'] . '.mp3', $audioStream);

        $storyPart->audio_file = $folderPath . $chapter->id . '_' . $storyPart['id'] . '.mp3';
        $storyPart->save();
    }
}
