<?php

namespace App\Livewire\Front;

use App\Models\AkadType;
use App\Models\AppSetting;
use App\Models\Banner;
use App\Models\Category;
use App\Models\FoundationSetting;
use App\Models\Program;
use Illuminate\Support\Facades\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

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
        $this->foundation = FoundationSetting::first() ?? new FoundationSetting([
            'name' => 'Yayasan',
            'about' => 'Deskripsi yayasan belum diatur.',
        ]);

        $this->banners = Banner::activeBanner()
            ->forPlacement('home')
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->featuredPrograms = Program::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(5)
            ->get();

        $this->otherPrograms = Program::where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        $this->categories = Category::where('is_active', true)->get();
        $this->akads = AkadType::where('is_active', true)->get();
    }

    public function render()
    {
        $logo = $this->foundation->logo;
        if ($logo && !str_starts_with($logo, 'http')) {
            $logo = url($logo);
        }

        return view('livewire.front.home')->layout('layouts.front', [
            'title'           => $this->foundation->name,
            'metaDescription' => \Illuminate\Support\Str::limit(strip_tags($this->foundation->about ?? ''), 160),
            'metaKeywords'    => 'donasi, yayasan, sedekah, zakat, infaq, qurban, galang dana, crowdfunding, ' . $this->foundation->name,
            'metaImage'       => $logo ?: null,
        ]);
    }

}