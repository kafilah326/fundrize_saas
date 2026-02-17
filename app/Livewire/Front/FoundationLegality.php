<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\LegalDocument;

class FoundationLegality extends Component
{
    public $documents;

    public function mount()
    {
        $this->documents = LegalDocument::orderBy('sort_order')->get();
    }

    #[Layout('layouts.front')]
    #[Title('Legalitas')]
    public function render()
    {
        return view('livewire.front.foundation-legality');
    }
}
