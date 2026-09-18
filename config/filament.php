<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Broadcasting (real-time notifications)
    |--------------------------------------------------------------------------
    |
    | Optional: uncomment the Echo configuration to connect Filament to any
    | Pusher-compatible websocket server.
    |
    */

    'broadcasting' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | The disk Filament uses to store uploaded files (see config/filesystems.php).
    |
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Assets Path
    |--------------------------------------------------------------------------
    |
    | Directory (relative to public/) where Filament publishes its assets.
    | After changing it, run: php artisan filament:assets
    |
    */

    'assets_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Cache Path
    |--------------------------------------------------------------------------
    |
    | Where Filament stores its component-registration cache.
    | After changing it, run: php artisan filament:cache-components
    |
    */

    'cache_path' => base_path('bootstrap/cache/filament'),

    /*
    |--------------------------------------------------------------------------
    | Livewire Loading Delay
    |--------------------------------------------------------------------------
    */

    'livewire_loading_delay' => 'default',

    /*
    |--------------------------------------------------------------------------
    | System Route Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix for Filament's internal routes (exports, failed imports, ...).
    |
    */

    'system_route_prefix' => 'filament',

    /*
    |--------------------------------------------------------------------------
    | Panel Branding (project specific)
    |--------------------------------------------------------------------------
    |
    | Read by App\Providers\Filament\AdminPanelProvider — the admin panel lives
    | at /admin (see FILAMENT_PATH) and is branded with your site name.
    |
    */

    'path' => env('FILAMENT_PATH', 'admin'),

    'domain' => env('FILAMENT_DOMAIN'),

    'brand' => env('FILAMENT_BRAND', 'DheyaDev CMS'),

];
