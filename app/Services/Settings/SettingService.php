<?php

namespace App\Services\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected const CACHE_PREFIX = 'system_setting_';

    /**
     * Get a setting by key.
     */
    public function get(string $key, $default = null)
    {
        return Cache::rememberForever(self::CACHE_PREFIX . $key, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value.
     */
    public function set(string $key, $value, string $group = 'general', bool $isEncrypted = false): void
    {
        $setting = Setting::firstOrNew(['key' => $key]);
        $setting->group = $group;
        $setting->is_encrypted = $isEncrypted;
        $setting->value = $value;
        $setting->save();

        Cache::forget(self::CACHE_PREFIX . $key);
    }

    /**
     * Get all settings in a specific group as associative array.
     */
    public function allByGroup(string $group): array
    {
        $settings = Setting::where('group', $group)->get();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->value;
        }
        return $result;
    }

    /**
     * Clear all settings cache.
     */
    public function forgetCache(?string $key = null): void
    {
        if ($key) {
            Cache::forget(self::CACHE_PREFIX . $key);
        } else {
            // Clear known setting keys
            $keys = Setting::pluck('key');
            foreach ($keys as $k) {
                Cache::forget(self::CACHE_PREFIX . $k);
            }
        }
    }
}
