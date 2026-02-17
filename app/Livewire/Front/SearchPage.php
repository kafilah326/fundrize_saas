<?php

namespace App\Livewire\Front;

use App\Models\Program;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class SearchPage extends Component
{
    public $query = '';

    public function getFilteredProgramsProperty()
    {
        if (empty($this->query)) {
            return [];
        }

        $programs = Program::where('is_active', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%'.$this->query.'%')
                    ->orWhereHas('categories', function ($c) {
                        $c->where('name', 'like', '%'.$this->query.'%');
                    });
            })
            ->get();

        return $this->mapPrograms($programs);
    }

    public function getUrgentProgramsProperty()
    {
        $programs = Program::where('is_active', true)
            ->where('is_urgent', true)
            ->orderBy('end_date', 'asc')
            ->take(2)
            ->get();

        return $this->mapPrograms($programs);
    }

    public function getFeaturedProgramsProperty()
    {
        $programs = Program::where('is_active', true)
            ->orderBy('donor_count', 'desc')
            ->take(2)
            ->get();

        return $this->mapPrograms($programs);
    }

    protected function mapPrograms($programs)
    {
        return $programs->map(function ($program) {
            return [
                'title' => $program->title,
                'slug' => $program->slug,
                'image' => $program->image,
                'days_left' => $program->days_left,
                'target' => $program->target_amount,
                'collected' => $program->collected_amount,
                'donor_count' => $program->donor_count,
                'category' => $program->categories->first()->name ?? 'Umum',
            ];
        });
    }

    #[Layout('layouts.front')]
    #[Title('Cari Program')]
    public function render()
    {
        return view('livewire.front.search-page', [
            'results' => $this->filteredPrograms,
            'urgentPrograms' => $this->urgentPrograms,
            'featuredPrograms' => $this->featuredPrograms,
        ]);
    }
}
