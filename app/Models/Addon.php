<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'type',
        'target',
        'value',
        'duration',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'value' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }
}
