<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\FoundationSetting;

class FoundationProfile extends Component
{
    public $foundation;

    public function mount()
    {
        $this->foundation = FoundationSetting::firstOrFail();
    }

    #[Layout('layouts.front')]
    #[Title('Profil Yayasan')]
    public function render()
    {
        return view('livewire.front.foundation-profile');
    }
}
