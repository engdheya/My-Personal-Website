<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@php
    $today = now()->format('Y-m-d');
    $staticRoutes = [
        ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
        ['url' => '/about', 'priority' => '0.8', 'changefreq' => 'weekly'],
        ['url' => '/projects', 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['url' => '/blog', 'priority' => '0.9', 'changefreq' => 'daily'],
        ['url' => '/services', 'priority' => '0.8', 'changefreq' => 'weekly'],
        ['url' => '/contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => '/privacy-policy', 'priority' => '0.3', 'changefreq' => 'yearly'],
        ['url' => '/terms', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ];
@endphp
@foreach ($staticRoutes as $route)
    <url>
        <loc>{{ $baseUrl }}{{ $route['url'] }}</loc>
        <lastmod>{{ $today }}</lastmod>
        <changefreq>{{ $route['changefreq'] }}</changefreq>
        <priority>{{ $route['priority'] }}</priority>
    </url>
@endforeach
@foreach ($posts as $post)
    <url>
        <loc>{{ $baseUrl }}/blog/{{ $post->slug }}</loc>
        <lastmod>{{ ($post->updated_at ?: $post->published_at)?->format('Y-m-d') ?: $today }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
@foreach ($projects as $project)
    <url>
        <loc>{{ $baseUrl }}/projects/{{ $project->slug }}</loc>
        <lastmod>{{ $project->updated_at?->format('Y-m-d') ?: $today }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
@foreach ($categories as $category)
    <url>
        <loc>{{ $baseUrl }}/blog?category={{ $category->slug }}</loc>
        <lastmod>{{ $category->updated_at?->format('Y-m-d') ?: $today }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
@endforeach
</urlset>
