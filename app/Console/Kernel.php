<?php

namespace App\Console;

use App\Actions\ChapterUpdated\GenerateStoryParts;
use App\Actions\GenerateImage;
use App\Actions\GetImage;
use App\Actions\GetToken;
use App\Actions\ImageGeneration\GenerateStoryImageDescriptions;
use App\Actions\ImageGeneration\GenerateStoryImages;
use App\Actions\TextToSpeech\GenerateStoryAudioForAllChapterParts;
use App\Actions\TextToSpeech\GenerateTextToSpeech;
use App\Actions\VideoGeneration\GenerateVideoDataInput;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        GetToken::class,
        GenerateImage::class,
        GetImage::class,

        // prompts
        GenerateStoryImageDescriptions::class,
        GenerateStoryImages::class,

        // chapters
         GenerateStoryParts::class,

        // tts
         GenerateStoryAudioForAllChapterParts::class,
         GenerateTextToSpeech::class,

        // video
         GenerateVideoDataInput::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
