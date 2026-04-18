<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantAddon extends Model
{
    protected $fillable = [
        'tenant_id',
        'addon_id',
        'purchased_at',
        'expires_at',
        'status',
        'amount_paid',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount_paid' => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            });
    }
}
