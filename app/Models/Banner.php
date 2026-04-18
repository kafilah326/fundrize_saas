<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use BelongsToTenant;

    use HasFactory;

    protected $guarded = []; // Allow all fields to be filled for now

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function scopeForPlacement($query, $placement)
    {
        return $query->where('placement', $placement);
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
        if (! $value) {
            return 'https://placehold.co/600x400?text=No+Banner';
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
    }
}
