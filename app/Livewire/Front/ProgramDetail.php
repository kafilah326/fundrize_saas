<?php

namespace App\Livewire\Front;

use App\Models\Program;
use Livewire\Component;

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

    public function render()
    {
        return view('livewire.front.program-detail')->layout('layouts.front', [
            'title' => $this->program->title.' - Yayasan Peduli',
            'metaDescription' => \Illuminate\Support\Str::limit(strip_tags($this->program->description), 160),
            'metaImage' => str_starts_with($this->program->image, 'http')
                                    ? $this->program->image
                                    : url($this->program->image),
            'metaKeywords' => 'donasi, '.implode(', ', $this->program->categories->pluck('name')->toArray()).', '.$this->program->title,
        ]);

    }
}
