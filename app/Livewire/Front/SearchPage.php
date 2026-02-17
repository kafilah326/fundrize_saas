<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Program;

class SearchPage extends Component
{
    public $query = '';

    public function getFilteredProgramsProperty()
    {
        if (empty($this->query)) {
            return [];
        }

        return Program::where('is_active', true)
            ->where(function($q) {
                $q->where('title', 'like', '%' . $this->query . '%')
                  ->orWhereHas('categories', function($c) {
                      $c->where('name', 'like', '%' . $this->query . '%');
                  });
            })
            ->get();
    }

    public function getUrgentProgramsProperty()
    {
        return Program::where('is_active', true)
            ->where('is_urgent', true)
            ->orderBy('end_date', 'asc')
            ->take(2)
            ->get();
    }

    public function getFeaturedProgramsProperty()
    {
        return Program::where('is_active', true)
            ->orderBy('donor_count', 'desc')
            ->take(2)
            ->get();
    }

    #[Layout('layouts.front')]
    #[Title('Cari Program')]
    public function render()
    {
        return view('livewire.front.search-page', [
            'results' => $this->filteredPrograms,
            'urgentPrograms' => $this->urgentPrograms,
            'featuredPrograms' => $this->featuredPrograms
        ]);
    }
}
