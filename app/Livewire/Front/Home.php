<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Program;
use App\Models\Banner;
use App\Models\Category;
use App\Models\AkadType;
use App\Models\FoundationSetting;

class Home extends Component
{
    public $featuredPrograms;
    public $otherPrograms;
    public $categories;
    public $akads;
    public $foundation;
    public $banners;

    public function mount()
    {
        $this->foundation = FoundationSetting::firstOrFail();
        
        $this->banners = Banner::activeBanner()
            ->forPage('home')
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->featuredPrograms = Program::where('is_active', true)
            ->where('is_featured', true)
            ->skip(3) // Skip the ones in slider
            ->take(3)
            ->get();

        $this->otherPrograms = Program::where('is_active', true)
            ->where('is_featured', false)
            ->latest()
            ->take(5)
            ->get();

        $this->categories = Category::where('is_active', true)->get();
        $this->akads = AkadType::where('is_active', true)->get();
    }

    #[Layout('layouts.front')]
    #[Title('Home')]
    public function render()
    {
        return view('livewire.front.home');
    }
}
