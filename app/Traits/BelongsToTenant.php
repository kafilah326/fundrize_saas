<?php

namespace App\Traits;

use App\Models\Tenant;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        // Auto-set tenant_id saat creating
        static::creating(function ($model) {
            if (app()->bound('current_tenant')) {
                $model->tenant_id = app('current_tenant')->id;
            }
        });

        // Global scope: otomatis filter by tenant
        static::addGlobalScope('tenant', function ($query) {
            if (app()->bound('current_tenant')) {
                $query->where($query->getModel()->getTable() . '.tenant_id', app('current_tenant')->id);
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
