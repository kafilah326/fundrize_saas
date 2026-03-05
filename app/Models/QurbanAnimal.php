<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbanAnimal extends Model
{
    protected $fillable = [
        'type',
        'name',
        'category',
        'weight',
        'price',
        'stock',
        'image',
        'description',
        'is_active',
        'commission_type',
        'commission_amount',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeLangsung($query)
    {
        return $query->where('type', 'langsung');
    }

    public function scopeTabungan($query)
    {
        return $query->where('type', 'tabungan');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageAttribute($value)
    {
        if (!$value) {
            return 'https://placehold.co/600x400?text=No+Image';
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
    }
}
