<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Jfcherng\Diff\DiffHelper;
use Livewire\Attributes\Computed;
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

        $response = Http::withHeader('Authorization', 'Bearer '.env('PERPLEXITY_KEY'))
            ->post('https://api.perplexity.ai/chat/completions', [
                'model' => 'pplx-70b-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an expert German professor. The user will give you a paragraph in German. Reply with a version of the paragraph, minimally corrected for spelling and grammar.

Here are some rules:
1. Do not explain your answer. Just correct the text. Your result should look very similar to the user's input, with no extra.
2. Do not finish or continue the user's writing. Do not add any new writing.
3. Do not translate into English.
4. If the user's text is a fragment, keep it as a fragment. The user may not be done writing, and that's okay.

You got this! If you do a good job, I will give you a $200 tip.",
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
