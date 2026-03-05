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
        
        // Get image from model (which already uses Storage url helper)
        $image = trim($this->program->image);
        
        // Fallback to foundation logo if image is empty or placeholder
        if (!$image || str_contains($image, "placehold.co")) {
            $image = $foundation ? trim($foundation->logo) : "";
        }
        
        // Final sanity check: ensure it starts with http
        if ($image && !str_starts_with($image, "http")) {
            $image = url($image);
        }

        return view("livewire.front.program-detail")
            ->layout("layouts.front", [
                "title" => trim($this->program->title),
                "metaDescription" => trim(\Illuminate\Support\Str::limit(strip_tags($this->program->description), 160)),
                "metaImage" => $image,
            ]);
    }
}
