<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\QurbanAnimal;
use App\Models\Banner;

class QurbanIndex extends Component
{
    public $animals;
    public $banner;

    public function mount()
    {
        $this->animals = QurbanAnimal::where('is_active', true)->where('type', 'langsung')->get();
        
        $this->banner = Banner::activeBanner()
            ->forPage('qurban')
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function selectAnimal($animalId)
    {
        $selectedAnimal = $this->animals->firstWhere('id', $animalId);

        if ($selectedAnimal) {
            session(['selected_animal' => $selectedAnimal]);
            return redirect()->route('qurban.checkout');
        }
    }

    #[Layout('layouts.front')]
    #[Title('Qurban')]
    public function render()
    {
        return view('livewire.front.qurban-index');
    }
}
