<?php

namespace App\Actions\ImageGeneration;

use App\Models\ChapterPart;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

//use OpenAI\Laravel\Facades\OpenAI;
use OpenAI;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class GenerateStoryImages
{
    use AsAction;

    public string $commandSignature = 'story:ai:story-images';

    public function handle(string $prompt): void
    {

        ray($this->prompt());

        $response = OpenAI\Laravel\Facades\OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 100,
        ]);

        ray($response->choices[0]->text);

        $response = OpenAI\Laravel\Facades\OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'max_tokens' => 2048,
            'messages' => [
                ['role' => 'system', 'content' => $this->prompt()],
                ['role' => 'system', 'content' => $this->userPrompt()],
            ],
        ]);

        ray($response);
//
//        foreach ($response->choices as $result) {
//            ray($result, $result->message);
//        }


    }

    public function asCommand(Command $command): void
    {
        $message = $this->handle($command->ask('Prompt'));

    }

    public function asJob(string $prompt, string $promptSuffix, ChapterPart $chapterPart): void
    {
        $this->handle($prompt, $promptSuffix, $chapterPart);
    }

    private function prompt(): string
    {

        $prompt = <<<EOT
Create an asset list of paintings required for each part of this story. Each description should begin with "a painting of [3-8 words to describe the asset]". Your response should be formatted as an array of json objects, where each object consists of the original story part and the asset description.

Your first sentence is: "As the longest day of the year finally arrived, the Sun and the Moon eagerly took their new positions in the sky."

For example:

```
[
  {
    "story": "As the longest day of the year finally arrived, the Sun and the Moon eagerly took their new positions in the sky.",
    "asset": "a[n] [adjective*] painting of [3-8 words to describe the asset]"
  }
]
```

You can also use an optional [adjective] to describe what is needed for the painting. For example "a close up painting of", or "a dark painting of"

Each story value should be unique, and not repeated.

Each asset description should stand on its own. We should assume the artists are independent and have no awareness of the total asset list. As such, any references to characters should be abstracted. For example, instead of referencing a specific Pronoun, like Queen Esmerelda, simply refer to a Queen, a Fox, a Sun, etc.

Now I'm going to give you the Story.
EOT;

        return $prompt;
    }

    private function userPrompt(): string
    {

        $prompt = <<<EOT
Chapter 2: A Day of Surprises

As the longest day of the year finally arrived, the Sun and the Moon eagerly took their new positions in the sky. The Sun, now in the Moon's place, attempted to create a calm and tranquil night by dimming its brilliant light. However, despite its best efforts, the Sun's radiance was still far too bright for the nocturnal world. This caused confusion among the night creatures, such as owls and bats, who found it difficult to navigate and hunt in the unusual brightness.

Meanwhile, the Moon, now in the Sun's place, was thrilled to see the world bustling with activity during the daytime. However, its soft, silvery glow was not enough to light up the day as the Sun usually did. The dim light made it difficult for the daytime creatures, like birds and squirrels, to go about their daily activities. They squinted and stumbled, unsure of how to adapt to the sudden darkness.

As the day wore on, the unexpected consequences of the Sun and Moon's switch became more apparent. The Wind, Clouds, and Stars tried to help their friends by compensating for the unusual lighting conditions. However, their attempts only seemed to cause more chaos. The Wind, in its eagerness to help, blew too hard and caused the ocean's waves to become unruly, making life difficult for the sea creatures. The Clouds, wanting to give the Moon a chance to shine brighter, accidentally blocked its already faint light, casting eerie shadows across the land.

Throughout the day, the Sun and Moon began to realize the challenges of each other's roles. They shared comical interactions with their friends as they tried to adapt to their new positions. The Sun, in a moment of frustration, tried to hide behind a group of clouds, only to accidentally create a breathtaking aurora in the night sky. The Moon, attempting to appear brighter, enlisted the help of the Stars, who formed a dazzling but disorganized constellation around it.

As the longest day of the year drew to a close, the Sun and the Moon couldn't help but laugh at the unexpected events that had unfolded. Despite the chaos, they had learned valuable lessons about each other's roles and gained a newfound appreciation for the delicate balance they maintained in the Sky Kingdom.

The End.
EOT;

        return $prompt;
    }

}
