<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Banner;
use App\Models\QurbanTabunganSetting;

class QurbanTabungan extends Component
{
    public $banner;
    public $settings;

    public function mount()
    {
        $this->banner = Banner::activeBanner()
            ->forPage('qurban_tabungan')
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->first();

        $this->settings = QurbanTabunganSetting::firstOrFail();
    }

    #[Layout('layouts.front')]
    #[Title('Tabungan Qurban')]
    public function render()
    {
        return view('livewire.front.qurban-tabungan');
    }
}
