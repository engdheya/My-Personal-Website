<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Canonical domain used by the sitemap and robots.txt.
     *
     * In production the app URL is used; locally the live domain is kept so
     * the generated sitemap always points at the public website.
     */
    protected function baseUrl(): string
    {
        if (app()->isProduction() && filled(config('app.url'))) {
            return rtrim((string) config('app.url'), '/');
        }

        return 'https://dheyadev.com';
    }

    public function index(): Response
    {
        $baseUrl = $this->baseUrl();

        $posts = Post::published()
            ->select(['slug', 'updated_at', 'published_at'])
            ->latest('published_at')
            ->get();

        $projects = Project::select(['slug', 'updated_at'])->latest('id')->get();

        $categories = Category::select(['slug', 'updated_at'])->get();

        $content = view('sitemap', compact('baseUrl', 'posts', 'projects', 'categories'))->render();

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $baseUrl = $this->baseUrl();

        $content = "User-agent: *\n"
            ."Disallow: /admin\n"
            ."Disallow: /admin/\n"
            ."Allow: /\n\n"
            ."Sitemap: {$baseUrl}/sitemap.xml\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
