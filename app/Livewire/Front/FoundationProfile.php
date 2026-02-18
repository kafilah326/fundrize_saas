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
        return view('livewire.front.foundation-profile')->layout('layouts.front', [
            'title' => 'Tentang Kami - ' . $this->foundation->name,
            'metaDescription' => strip_tags($this->foundation->about),
            'metaKeywords' => 'profil yayasan, tentang kami, visi misi, ' . $this->foundation->name,
            'metaImage' => $this->foundation->logo,
        ]);
    }
}
