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
        $this->program = Program::with(['categories', 'akads', 'updates', 'distributions'])->where('slug', $slug)->firstOrFail();

        $this->donations = $this->program->donations()
            ->where('status', 'success')
            ->with('payment')
            ->latest()
            ->get();

        $this->programFundraisers = \App\Models\Fundraiser::whereHas('donations', function($q) {
                $q->where('program_id', $this->program->id)
                  ->where('status', 'success');
            })
            ->withCount(['donations as donor_count' => function($q) {
                $q->where('program_id', $this->program->id)
                  ->where('status', 'success');
            }])
            ->withSum(['donations as total_amount' => function($q) {
                $q->where('program_id', $this->program->id)
                  ->where('status', 'success');
            }], 'amount')
            ->orderByDesc('total_amount')
            ->get();
    }

    public function render()
    {
        $foundation = \App\Models\FoundationSetting::first();

        // Get the raw image value from database to avoid accessor interference
        $imagePath = $this->program->getRawOriginal('image');
        $finalImage = '';

        if ($imagePath && ! str_contains($imagePath, 'placehold.co')) {
            // If already an absolute URL (e.g. external CDN), use directly
            if (str_starts_with($imagePath, 'http')) {
                $finalImage = $imagePath;
            } else {
                $finalImage = \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath);
            }
        } else {
            // Fallback to foundation logo
            $logoPath = $foundation ? $foundation->getRawOriginal('logo') : null;
            if ($logoPath) {
                if (str_starts_with($logoPath, 'http')) {
                    $finalImage = $logoPath;
                } else {
                    $finalImage = \Illuminate\Support\Facades\Storage::disk('public')->url($logoPath);
                }
            }
        }

        // Ensure absolute URL
        if ($finalImage && ! str_starts_with($finalImage, 'http')) {
            $finalImage = url($finalImage);
        }

        // Null if empty — layout will use default-og.jpg fallback
        if (! $finalImage) {
            $finalImage = null;
        }

        return view('livewire.front.program-detail')->layout('layouts.front', [
            'title' => trim($this->program->title),
            'metaDescription' => trim(\Illuminate\Support\Str::limit(strip_tags($this->program->description ?? ''), 160)),
            'metaImage' => $finalImage,
        ]);
    }
}
