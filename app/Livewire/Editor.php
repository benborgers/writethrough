<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Jfcherng\Diff\DiffHelper;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Editor extends Component
{
    public $content = '';

    #[Computed]
    public function response()
    {
        if (str($this->content)->isEmpty()) {
            return '';
        }

        $response = Http::withHeader('Authorization', 'Bearer '.env('PERPLEXITY_KEY'))
            ->post('https://api.perplexity.ai/chat/completions', [
                'model' => 'pplx-70b-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert German professor. The user will give you a paragraph in German. Reply with a version of the paragraph, minimally corrected for spelling and grammar. Do not write anything other than the corrected text, i.e. do not explain or prefix your answer. If the user\'s text is a fragment, keep it as a fragment.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->content,
                    ],
                ],
            ]);

        $corrected = $response->json()['choices'][0]['message']['content'];

        $diff = DiffHelper::calculate(
            trim($this->content)."\n", // For some reason, the addition of a newline helps the diffing library.
            trim($corrected),
            'Combined',
            [],
            [
                'detailLevel' => 'word',
            ]
        );

        return $diff;
    }

    public function render()
    {
        return view('livewire.editor');
    }
}
