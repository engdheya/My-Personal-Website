<?php

return [
    'path' => env('FILAMENT_PATH', 'admin'),
    'domain' => env('FILAMENT_DOMAIN'),
    'home_url' => '/',
    'brand' => 'DheyaDev CMS',
    'layout' => [
        'sidebar' => [
            'is_collapsible_on_desktop' => true,
            'collapsed_width' => '4.5rem',
            'width' => '16rem',
        ],
    ],
    'dark_mode' => true,
];
