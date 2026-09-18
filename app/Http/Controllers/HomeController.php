<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::query()
            ->where('is_featured', true)
            ->latest('id')
            ->take(3)
            ->get();

        $latestPosts = Post::published()
            ->with('category')
            ->latest('published_at')
            ->take(3)
            ->get();

        $services = Service::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->take(4)
            ->get();

        return view('home', compact('featuredProjects', 'latestPosts', 'services'));
    }

    public function about()
    {
        $breadcrumbs = [
            ['name' => __('site.nav_about'), 'url' => '/about'],
        ];

        return view('about', compact('breadcrumbs'));
    }
}
