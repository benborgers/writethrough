<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;

class Editor extends Component
{
    public $content = 'Hey';

    #[Computed]
    public function response()
    {
        return $this->content;
    }

    public function render()
    {
        return view('livewire.editor');
    }
}
