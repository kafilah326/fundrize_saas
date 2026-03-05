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
        $foundation = \App\Models\FoundationSetting::first();
        
        // Simple Absolute Image URL
        $image = $this->program->image;
        if (!$image || str_contains($image, "placehold.co")) {
            $image = $foundation->logo ?? "";
        }
        
        // Force URL helper to ensure absolute path if not already http
        if ($image && !str_starts_with($image, "http")) {
            $image = url($image);
        }

        return view("livewire.front.program-detail")
            ->layout("layouts.front", [
                "title" => $this->program->title,
                "metaDescription" => \Illuminate\Support\Str::limit(strip_tags($this->program->description), 160),
                "metaImage" => $image,
            ]);
    }
}
