<?php

namespace App\Actions;

use App\Models\ChapterPart;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

use Ferranfg\MidjourneyPhp\Midjourney;

class GenerateImage
{
    use AsAction;

    private $promptSuffix = ', in the style of waldorf, pastel-colored landscapes, spiritual symbolism, prairiecore, chalk, environmental activism, luminescence, byam shaw --ar 16:9 --v 5.1';

    public string $commandSignature = 'story:mj:imagine';

    public function handle($prompt, $promptSuffix, ChapterPart $chapterPart = null): void
    {

        $channelId = config('services.midjourney.discord_channel_id');
        $userToken = config('services.midjourney.discord_user_token');

        ray($channelId, $userToken);

        $midjourney = new Midjourney($channelId, $userToken);

        $message = $midjourney->imagine($prompt . ", " . $promptSuffix);

        if ($chapterPart) {
            ray($chapterPart->id, $message);

            if (isset($message->raw_message->attachments[0]->url)) {
                $attachmentUrl = $message->raw_message->attachments[0]->url;
            } else {
                $attachmentUrl = "404";
            }

            $chapterPart->image_url = $attachmentUrl;
            $chapterPart->save();
        }

    }

    public function asCommand(Command $command): void
    {
        $message = $this->handle($command->ask('Prompt'), $command->ask('Prompt Suffix'));

    }

    public function asJob(string $prompt, string $promptSuffix, ChapterPart $chapterPart): void
    {
        $this->handle($prompt, $promptSuffix, $chapterPart);
    }

}
