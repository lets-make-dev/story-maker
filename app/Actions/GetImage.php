<?php

namespace App\Actions;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

use Ferranfg\MidjourneyPhp\Midjourney;

class GetImage
{
    use AsAction;

//    private $promptSuffix = ', in the style of waldorf, pastel-colored landscapes, spiritual symbolism, prairiecore, chalk, environmental activism, luminescence, byam shaw --ar 16:9 --v 5';

    public string $commandSignature = 'story:mj:get';

    public function handle($prompt, $promptSuffix)
    {

        $channelId = config('services.midjourney.discord_channel_id');
        $userToken = config('services.midjourney.discord_user_token');

        ray($channelId, $userToken);

        $midjourney = new Midjourney($channelId, $userToken);

        $message = $midjourney->getImagine($prompt . $promptSuffix);

        return $message;

    }

    public function asCommand(Command $command): void
    {
        $message = $this->handle($command->ask('Prompt'), $command->ask('Prompt Suffix'));

        print_r($message);
    }

}
