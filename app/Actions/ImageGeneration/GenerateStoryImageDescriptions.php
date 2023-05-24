<?php

namespace App\Actions\ImageGeneration;

use App\Models\Chapter;
use App\Models\ChapterPart;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

//use OpenAI\Laravel\Facades\OpenAI;
use OpenAI;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class GenerateStoryImageDescriptions
{
    use AsAction;

    public string $commandSignature = 'story:ai:story-paintings';

    public int $jobBackoff = 10;
    public $tries = 3;

    public function handle(Chapter $chapter): void
    {
        $args = [
            'model' => 'gpt-3.5-turbo',
            'max_tokens' => 2048,
            'messages' => [
                ['role' => 'system', 'content' => $this->systemPrompt()],
                ['role' => 'user', 'content' => $this->userPrompt($chapter)],
            ],
        ];

        ray($args);

        $response = OpenAI\Laravel\Facades\OpenAI::chat()->create($args);

        foreach ($response->choices as $result) {

            ray($response);

            // decode $result as JSON
            sleep(2); // wait 2 seconds
            $results = json_decode($result->message->content);

            foreach($results as $storyElement) {
                ray($storyElement);
                if ($storyElement->asset ?? false) {
                    $chapterPart = ChapterPart::findOrFail($storyElement->id);
                    $chapterPart->image_description = $storyElement->asset;
                    $chapterPart->save();
                }
            }
        }
    }

    public function asCommand(Command $command): void
    {
        $chapterId = $command->ask('Chapter ID');

        $chapter = Chapter::findOrFail($chapterId);

        $this->handle($chapter);

    }

    public function asJob(Chapter $chapter): void
    {
        $this->handle($chapter);
    }

    private function systemPrompt(): string
    {

        $prompt = <<<EOT
You act as an expert at providing a book illustrator with descriptive ideas for paintings to be included into a children's book.

Each description you provide begins with "a[n] [adjective*] painting of [3-8 words to describe the asset]".

You can also use an optional [adjective] to describe what is needed for the painting. For example "a close up painting of", or "a dark painting of"

Each asset description should stand on its own. We should assume the artists are independent and have no awareness of the total asset list. As such, any references to characters should be abstracted. For example, instead of referencing a specific Pronoun, like Queen Esmerelda, simply refer to a Queen, a Fox, a Sun, etc.

You should also generate an asset for each heading. Each heading represents a chapter containing the story elements before the next heading but offer some foreshadowing.

Your first task is to iterate over the JSON array and add an impactful `asset` key  and description where appropriate. Format your response as:

[
{ "id": [id], "heading": [text], "asset": [description representing the story the heading represents] },
{ "id": [id], "story": [text], "asset": [description] },
...
]
EOT;

        return $prompt;
    }

    /**
     * @param Chapter $chapter
     * @return void
     */
    private function userPrompt(Chapter $chapter): string
    {
        $chapterParts = ChapterPart::select('id', 'story')->where('chapter_id', $chapter->id)->get();

        $data = [];

        foreach ($chapterParts as $chapterPart) {
            $storyElement = [
                'id' => $chapterPart->id,
            ];

            if ($chapterPart->story[0] === '#') {
                $storyElement['heading'] = $chapterPart->story;
            } else {
                $storyElement['story'] = $chapterPart->story;
            }

            $data[] = $storyElement;
        }

        return json_encode($data);
    }

}
