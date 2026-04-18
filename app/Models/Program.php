<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'image',
        'description',
        'is_dynamic',
        'package_price',
        'package_label',
        'target_amount',
        'collected_amount',
        'donor_count',
        'end_date',
        'is_active',
        'is_featured',
        'is_urgent',
        'commission_type',
        'commission_amount',
    ];

    protected $casts = [
        'is_dynamic' => 'boolean',
        'package_price' => 'decimal:2',
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'program_category');
    }

    public function akads(): BelongsToMany
    {
        return $this->belongsToMany(AkadType::class, 'program_akad');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ProgramUpdate::class);
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(ProgramDistribution::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function getProgressAttribute()
    {
        if ($this->target_amount === null || $this->target_amount <= 0) return null;
        return min(100, round(($this->collected_amount / $this->target_amount) * 100));
    }

    public function getDaysLeftAttribute()
    {
        if (!$this->end_date) return null;
        return (int) max(0, now()->diffInDays($this->end_date, false));
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
