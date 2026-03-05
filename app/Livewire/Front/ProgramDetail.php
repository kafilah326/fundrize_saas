<?php

namespace App\Livewire\Front;

use App\Models\Program;
use Livewire\Component;

class ProgramDetail extends Component
{
    public $slug;

    public $program;

    public $donations;

    public $programFundraisers;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->program = Program::with(['categories', 'updates', 'distributions'])->where('slug', $slug)->firstOrFail();

        $this->donations = $this->program->donations()
            ->where('status', 'success')
            ->with('payment')
            ->latest()
            ->get();
            
        $this->programFundraisers = \App\Models\Fundraiser::select('fundraisers.id', 'fundraisers.name', 'fundraisers.user_id')
            ->join('donations', 'fundraisers.id', '=', 'donations.fundraiser_id')
            ->where('donations.program_id', $this->program->id)
            ->where('donations.status', 'success')
            ->selectRaw('COUNT(donations.id) as donor_count')
            ->selectRaw('SUM(donations.amount) as total_amount')
            ->groupBy('fundraisers.id', 'fundraisers.name', 'fundraisers.user_id')
            ->orderByDesc('total_amount')
            ->get();
    }

    public function render()
    {
        $foundationName = \App\Models\FoundationSetting::value('name') ?? 'Yayasan Peduli';

        return view('livewire.front.program-detail')->layout('layouts.front', [
            'title' => $this->program->title.' - ' . $foundationName,
            'metaDescription' => \Illuminate\Support\Str::limit(strip_tags($this->program->description), 160),
            'metaImage' => str_starts_with($this->program->image, 'http')
                                    ? $this->program->image
                                    : url($this->program->image),
            'metaKeywords' => 'donasi, '.implode(', ', $this->program->categories->pluck('name')->toArray()).', '.$this->program->title,
        ]);
    }
}
