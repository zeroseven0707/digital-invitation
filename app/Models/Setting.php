<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'description', 'is_public'];

    protected $casts = ['is_public' => 'boolean'];

    // ─── Cache key ────────────────────────────────────────────────────────────
    const CACHE_KEY = 'app_settings';
    const CACHE_TTL = 3600; // 1 jam

    // ─── Get single setting ───────────────────────────────────────────────────
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::getAllCached();
        if (!isset($settings[$key])) return $default;

        return static::castValue($settings[$key]['value'], $settings[$key]['type']);
    }

    // ─── Set single setting ───────────────────────────────────────────────────
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value]
        );
        Cache::forget(static::CACHE_KEY);
    }

    // ─── Get all settings (cached) ────────────────────────────────────────────
    public static function getAllCached(): array
    {
        return Cache::remember(static::CACHE_KEY, static::CACHE_TTL, function () {
            return static::all()->keyBy('key')->map(fn($s) => [
                'value' => $s->value,
                'type'  => $s->type,
            ])->toArray();
        });
    }

    // ─── Clear cache ──────────────────────────────────────────────────────────
    public static function clearCache(): void
    {
        Cache::forget(static::CACHE_KEY);
    }

    // ─── Cast value by type ───────────────────────────────────────────────────
    private static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json'    => json_decode($value, true),
            default   => $value,
        };
    }
}
