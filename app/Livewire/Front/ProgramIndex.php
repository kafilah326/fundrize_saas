<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Program;
use App\Models\Category;
use App\Models\AkadType;
use Livewire\WithPagination;

class ProgramIndex extends Component
{
    use WithPagination;

    public $categories;
    public $akads;
    public $selectedAkad = [];
    public $selectedKategori = [];
    public $limit = 5;

    public function mount()
    {
        $this->categories = Category::where('is_active', true)->get();
        $this->akads = AkadType::where('is_active', true)->get();

        if (request()->has('category')) {
            $this->selectedKategori[] = request('category');
        }
        if (request()->has('akad')) {
            $this->selectedAkad[] = request('akad');
        }
    }

    public function loadMore()
    {
        $this->limit += 5;
    }

    public function toggleAkad($slug)
    {
        if ($slug == 'semua') {
            $this->selectedAkad = [];
        } else {
            if (in_array($slug, $this->selectedAkad)) {
                $this->selectedAkad = array_diff($this->selectedAkad, [$slug]);
            } else {
                $this->selectedAkad[] = $slug;
            }
        }
        $this->selectedAkad = array_values($this->selectedAkad);
        $this->resetPage();
    }

    public function toggleKategori($slug)
    {
        if ($slug == 'semua') {
            $this->selectedKategori = [];
        } else {
            if (in_array($slug, $this->selectedKategori)) {
                $this->selectedKategori = array_diff($this->selectedKategori, [$slug]);
            } else {
                $this->selectedKategori[] = $slug;
            }
        }
        $this->selectedKategori = array_values($this->selectedKategori);
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->selectedAkad = [];
        $this->selectedKategori = [];
        $this->resetPage();
    }

    #[Layout('layouts.front')]
    #[Title('Program')]
    public function render()
    {
        $query = Program::where('is_active', true);

        if (!empty($this->selectedAkad) && !in_array('semua', $this->selectedAkad)) {
            $query->whereHas('akads', function($q) {
                $q->whereIn('slug', $this->selectedAkad);
            });
        }

        if (!empty($this->selectedKategori) && !in_array('semua', $this->selectedKategori)) {
            $query->whereHas('categories', function($q) {
                $q->whereIn('slug', $this->selectedKategori);
            });
        }

        $programs = $query->latest()->take($this->limit)->get();
        $totalPrograms = $query->count();

        return view('livewire.front.program-index', [
            'programs' => $programs,
            'totalPrograms' => $totalPrograms,
        ]);
    }
}
