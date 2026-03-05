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
        $foundationName = $foundation->name ?? 'Yayasan Peduli';
        
        // Clean description for meta (max 160 chars)
        $rawDescription = $this->program->description;
        $cleanDescription = str_replace(['&nbsp;', '&amp;', "\r", "\n"], [' ', '&', ' ', ' '], strip_tags($rawDescription));
        $metaDescription = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', $cleanDescription)), 160);
        
        // Ensure absolute URL for image
        $image = $this->program->image;
        if (!$image || str_contains($image, 'placehold.co')) {
            $image = $foundation->logo ?? '';
        }
        
        if ($image && !str_starts_with($image, 'http')) {
            $image = url($image);
        }

        // Final fallback if still empty
        if (!$image) {
            $image = asset('images/default-og.jpg');
        }

        // Keywords from categories
        $categories = $this->program->categories->pluck('name')->toArray();
        $metaKeywords = 'donasi, sedekah, infaq, yayasan, ' . implode(', ', $categories) . ', ' . $this->program->title;

        return view('livewire.front.program-detail')
            ->layout('layouts.front', [
                'title' => $this->program->title . ' - ' . $foundationName,
                'metaDescription' => $metaDescription,
                'metaImage' => $image,
                'metaKeywords' => $metaKeywords,
                'metaAuthor' => $foundationName,
            ]);
    }
}
