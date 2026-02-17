<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AppSetting extends Model
{
    protected $fillable = [
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
        // Cache key
        $cacheKey = 'app_setting_' . $key;

        return Cache::rememberForever($cacheKey, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

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

            // Clear cache
            Cache::forget('app_setting_' . $key);
        }
    }
}
