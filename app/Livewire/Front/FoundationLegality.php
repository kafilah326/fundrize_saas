<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\LegalDocument;
use App\Models\FoundationSetting;

class FoundationLegality extends Component
{
    public $documents;
    public $foundation;

    public function mount()
    {
        $this->documents = LegalDocument::orderBy('sort_order')->get();
        $this->foundation = FoundationSetting::first();
    }

    #[Layout('layouts.front')]
    #[Title('Legalitas')]
    public function render()
    {
        $foundationName = $this->foundation->name ?? 'Yayasan Peduli';

        return view('livewire.front.foundation-legality')->layout('layouts.front', [
            'title' => 'Legalitas - ' . $foundationName,
        ]);
    }
}
