<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::featured()->latest()->take(3)->get();
        $latestPosts = Post::published()->with('category')->latest('published_at')->take(3)->get();
        $services = Service::orderBy('sort_order')->take(4)->get();

        return view('home', compact('featuredProjects', 'latestPosts', 'services'));
    }

    public function about()
    {
        return view('about');
    }
}
