<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'placement',
        'description',
        'link_url',
        'cta_text',
        'start_date',
        'end_date',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function scopeForPage($query, $page)
    {
        return $query->where('placement', $page);
    }

    public function scopeActiveBanner($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->where('start_date', '<=', now())
                  ->orWhereNull('start_date');
            })
            ->where(function ($q) {
                $q->where('end_date', '>=', now())
                  ->orWhereNull('end_date');
            });
    }

    public function getImageAttribute($value)
    {
        if (!$value) {
            return 'https://placehold.co/600x400?text=No+Banner';
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
    }
}
