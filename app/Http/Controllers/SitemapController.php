<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = 'https://dheyadev.com';
        $posts = Post::published()->select('slug', 'updated_at')->get();
        $projects = Project::select('slug', 'updated_at')->get();
        $categories = Category::select('slug', 'updated_at')->get();

        $content = view('sitemap', compact('baseUrl', 'posts', 'projects', 'categories'))->render();

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nDisallow: /admin\nDisallow: /admin/\nAllow: /\n\nSitemap: https://dheyadev.com/sitemap.xml\n";
        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
