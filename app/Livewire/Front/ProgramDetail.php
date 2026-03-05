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
        
        // Get the raw image value from database to avoid accessor interference if any
        $imagePath = $this->program->getRawOriginal("image");
        $finalImage = "";

        if ($imagePath && !str_contains($imagePath, "placehold.co")) {
            $finalImage = \Illuminate\Support\Facades\Storage::disk("public")->url($imagePath);
        } else {
            // Fallback to foundation logo
            $logoPath = $foundation ? $foundation->getRawOriginal("logo") : null;
            if ($logoPath) {
                $finalImage = \Illuminate\Support\Facades\Storage::disk("public")->url($logoPath);
            }
        }

        // Ensure absolute URL
        if ($finalImage && !str_starts_with($finalImage, "http")) {
            $finalImage = url($finalImage);
        }

        return view("livewire.front.program-detail")
            ->layout("layouts.front", [
                "title" => trim($this->program->title),
                "metaDescription" => trim(\Illuminate\Support\Str::limit(strip_tags($this->program->description), 160)),
                "metaImage" => $finalImage,
            ]);
    }
}
