<?php

namespace App\Livewire\Front;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Program;

class ProgramDetail extends Component
{
    public $slug;
    public $program;
    public $donations;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->program = Program::with(['categories', 'updates', 'distributions'])->where('slug', $slug)->firstOrFail();
        
        $this->donations = $this->program->donations()
            ->where('status', 'success')
            ->with('payment')
            ->latest()
            ->get();
    }

    #[Layout('layouts.front')]
    public function render()
    {
        return view('livewire.front.program-detail')->layout('layouts.front', [
            'title' => $this->program->title . ' - Yayasan Peduli',
            'metaDescription' => \Illuminate\Support\Str::limit(strip_tags($this->program->description), 160),
            'metaImage' => $this->program->image,
            'metaKeywords' => 'donasi, ' . implode(', ', $this->program->categories->pluck('name')->toArray()) . ', ' . $this->program->title,
        ]);
    }
}
