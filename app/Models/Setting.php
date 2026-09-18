<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value',
        'group_name',
    ];

    /**
     * All settings, loaded with a single query and cached for the request.
     *
     * @var array<string, mixed>|null
     */
    protected static ?array $requestCache = null;

    /**
     * @return array<string, mixed>
     */
    public static function allCached(): array
    {
        if (static::$requestCache !== null) {
            return static::$requestCache;
        }

        try {
            static::$requestCache = static::query()->pluck('value', 'key')->all();
        } catch (\Throwable $e) {
            // The table may not exist yet (before `php artisan migrate`);
            // never let a missing settings table break the whole website.
            static::$requestCache = [];
        }

        return static::$requestCache;
    }

    public static function flushCache(): void
    {
        static::$requestCache = null;
    }

    /**
     * Read a single setting value with a default fallback.
     */
    public static function read(string $key, $default = null)
    {
        $settings = static::allCached();

        if (array_key_exists($key, $settings) && filled($settings[$key])) {
            return $settings[$key];
        }

        return $default;
    }

    /**
     * Alias used by the `setting()` Blade helper.
     */
    public static function value($key, $default = null)
    {
        return static::read($key, $default);
    }

    public static function get($key, $default = null)
    {
        return static::read($key, $default);
    }

    public static function set($key, $value, $group = 'general')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group_name' => $group]
        );

        static::flushCache();

        return $setting;
    }

    /**
     * Is the settings table ready? Used by the xampp:check command.
     */
    public static function isReady(): bool
    {
        try {
            return Schema::hasTable('settings');
        } catch (\Throwable $e) {
            return false;
        }
    }
}
