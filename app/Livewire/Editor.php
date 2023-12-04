<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Jfcherng\Diff\DiffHelper;
use Livewire\Component;

class Editor extends Component
{
    public $content = '';

    public $fixed = '';

    public function fix()
    {
        if (str($this->content)->isEmpty()) {
            $this->fixed = '';
        }

        $response = Http::withHeader('Authorization', 'Bearer '.env('OPENAI_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "You are an expert German professor. I will give you an essay in German in my next message. Reply with a version of the essay, minimally corrected for spelling and grammar, so that it is good academic German writing.

Rules:
1. Do not explain your answer or add writing. Just correct the text.
2. Do not finish or continue the my writing. Do not add any new writing.
3. Do not translate my writing.
4. If my text is a fragment, keep it as a fragment. I may not be done writing.

You got this! If you follow the rules perfectly, I will give you a $200 tip.",
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->content,
                    ],
                ],
            ]);

        $corrected = $response->json()['choices'][0]['message']['content'];

        $this->fixed = DiffHelper::calculate(
            trim($this->content)."\n", // For some reason, the addition of a newline helps the diffing library.
            trim($corrected),
            'Combined',
            [],
            [
                'detailLevel' => 'word',
            ]
        );
    }
}
