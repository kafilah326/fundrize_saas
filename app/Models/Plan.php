<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'system_fee_percentage',
        'features',
        'limits',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'system_fee_percentage' => 'float',
        'features' => 'array',
        'limits' => 'array',
        'is_active' => 'boolean',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function hasFeature($key)
    {
        if (empty($this->features)) return false;
        return isset($this->features[$key]) && $this->features[$key] === true;
    }

    public function getLimit($key, $default = null)
    {
        if (empty($this->limits)) return $default;
        return isset($this->limits[$key]) ? $this->limits[$key] : $default;
    }
}
