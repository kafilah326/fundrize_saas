<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TenantDomain extends Model
{
    protected $fillable = [
        'tenant_id',
        'domain',
        'type',
        'is_primary',
        'ssl_status',
        'dns_target',
        'dns_verified',
        'last_checked_at',
        'verified_at',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'dns_verified' => 'boolean',
        'last_checked_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeCustom(Builder $query)
    {
        return $query->where('type', 'custom');
    }

    public function scopeVerified(Builder $query)
    {
        return $query->where('dns_verified', true)->orWhereNotNull('verified_at');
    }

    public function isVerified(): bool
    {
        return $this->type === 'subdomain' || ($this->type === 'custom' && $this->dns_verified);
    }

    public function isPending(): bool
    {
        return $this->type === 'custom' && !$this->dns_verified;
    }
}
