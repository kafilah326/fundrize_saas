<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AppSetting extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
    ];

    /**
     * Get a setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null): mixed
    {
        $tenantId = app()->bound('current_tenant') ? app('current_tenant')->id : 'global';
        // Cache key
        $cacheKey = "app_setting_{$tenantId}_{$key}";

        return Cache::rememberForever($cacheKey, function () use ($key, $default, $tenantId) {
            $query = self::where('key', $key);
            if ($tenantId !== 'global') {
                $query->where('tenant_id', $tenantId);
            }
            // If we don't have scoped setting, we can fall back to global (tenant_id = null) if we want,
            // but since AppSetting uses BelongsToTenant, it might auto filter by tenant.
            // Actullay BelongsToTenant handles tenant_id checking globally. So we shouldn't need to manually filter,
            // just fetching it will be scoped. But wait, we used `self::where('key', $key)` which IS scoped by trait.
            $setting = $query->first();

            if (!$setting) {
                return $default;
            }

            $value = $setting->value;

            if ($setting->type === 'encrypted' && !empty($value)) {
                try {
                    $value = Crypt::decryptString($value);
                } catch (\Exception $e) {
                    Log::error("Failed to decrypt setting {$key}: " . $e->getMessage());
                    return $default;
                }
            } elseif ($setting->type === 'boolean') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } elseif ($setting->type === 'number') {
                $value = is_numeric($value) ? (float) $value : (int) $value;
            }

            return $value;
        });
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            if ($setting->type === 'encrypted') {
                $value = Crypt::encryptString($value);
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            $setting->update(['value' => $value]);

            $tenantId = app()->bound('current_tenant') ? app('current_tenant')->id : 'global';
            // Clear cache
            Cache::forget("app_setting_{$tenantId}_{$key}");
        }
    }
}
