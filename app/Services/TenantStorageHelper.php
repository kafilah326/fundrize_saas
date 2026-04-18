<?php

namespace App\Services;

class TenantStorageHelper
{
    /**
     * Get the tenant-specific subpath for storage.
     * E.g., `tenants/1/banners`, `tenants/1/programs`
     */
    public static function path(string $subPath = ''): string
    {
        $tenantId = app()->bound('current_tenant') ? app('current_tenant')->id : 'global';
        $subPath = ltrim($subPath, '/');
        return "tenants/{$tenantId}/{$subPath}";
    }
}
