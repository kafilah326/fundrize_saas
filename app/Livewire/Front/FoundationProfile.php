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

    public function render()
    {
        $logo = $this->foundation->logo;
        if ($logo && !str_starts_with($logo, 'http')) {
            $logo = url($logo);
        }

        return view('livewire.front.foundation-profile')->layout('layouts.front', [
            'title'           => 'Tentang Kami - ' . $this->foundation->name,
            'metaDescription' => \Illuminate\Support\Str::limit(strip_tags($this->foundation->about ?? ''), 160),
            'metaKeywords'    => 'profil yayasan, tentang kami, visi misi, ' . $this->foundation->name,
            'metaImage'       => $logo ?: null,
        ]);
    }
}
