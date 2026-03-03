<?php

namespace App\Livewire\Front;

use App\Models\Banner;
use App\Models\QurbanAnimal;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class QurbanIndex extends Component
{
    public $animals;

    public $banner;

    public function mount()
    {
        $this->animals = QurbanAnimal::where('is_active', true)->where('type', 'langsung')->get();

        $this->banner = Banner::activeBanner()
            ->forPlacement('qurban')
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
        $foundationName = \App\Models\FoundationSetting::value('name') ?? 'Yayasan Peduli';

        return view('livewire.front.qurban-index')->layout('layouts.front', [
            'title' => 'Qurban Online Terpercaya - ' . $foundationName,
            'metaDescription' => 'Layanan Qurban Online mudah dan terpercaya. Tersedia berbagai pilihan hewan qurban (Sapi, Kambing, Domba) dengan harga terjangkau.',
            'metaKeywords' => 'qurban, qurban online, jual hewan qurban, sapi qurban, kambing qurban, domba qurban, ' . strtolower($foundationName),
        ]);
    }
}
