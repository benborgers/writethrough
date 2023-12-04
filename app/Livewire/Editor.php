<?php

namespace App\Livewire;

use Jfcherng\Diff\DiffHelper;
use Livewire\Component;
use OpenAI;

class Editor extends Component
{
    public $content = '';
    public $fixed = '';

    public function fix()
    {
        if (str($this->content)->isEmpty()) {
            $this->fixed = '';

            return;
        }

        $lastParagraph = str($this->content)->afterLast("\n");

        $client = OpenAI::client(env('OPENAI_KEY'));

        $stream = $client->chat()->createStreamed(
            [
                'model' => 'gpt-3.5-turbo',
                'max_tokens' => 1000,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'You are an expert German proofreader. I will give you an essay in German in my next message. Reply with a version of the essay, minimally corrected for spelling and grammar, so that it is good academic German writing.

Do not explain your answer. DO NOT invent more writing and append it. Only proofread. Do not translate my writing into English.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $lastParagraph,
                    ],
                ],
            ]
        );

        $this->fixed = '';
        foreach ($stream as $response) {
            $this->fixed .= $response->choices[0]->delta->content;
            $this->stream(to: 'fixed', content: $this->fixed, replace: true);
        }

        $diff = DiffHelper::calculate(
            trim($lastParagraph)."\n", // For some reason, the addition of a newline helps the diffing library.
            trim($this->fixed),
            'Combined',
            [],
            [
                'detailLevel' => 'word',
            ]
        );

        // This helps double-clicking to select replaced words and pasting them into your writing.
        $this->fixed = str($diff)->replace('</del><ins>', '</del>&ZeroWidthSpace;<ins>');
    }
}
