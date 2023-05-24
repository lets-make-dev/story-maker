<?php

namespace App\Actions;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class GetToken
{
    use AsAction;

    public string $commandSignature = 'story:mj:get-token';

    public function handle(): string
    {

        return "https://discord.com/channels/@me";

    }

    public function asCommand(Command $command): void
    {
        $link = $this->handle();

        $command->info($link);
    }
}
