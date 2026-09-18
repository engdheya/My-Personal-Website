<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('dheyadev:stats', function () {
    $this->info('DheyaDev content overview');
    $this->table(
        ['Table', 'Rows'],
        [
            ['posts', \App\Models\Post::count()],
            ['projects', \App\Models\Project::count()],
            ['services', \App\Models\Service::count()],
            ['messages', \App\Models\Message::count()],
        ]
    );
})->purpose('Show a quick content summary');
