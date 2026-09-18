<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $posts = [];
        $projects = [];

        if ($query) {
            $posts = Post::published()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                })->take(20)->get();

            $projects = Project::where('title', 'like', "%{$query}%")
                ->orWhere('technologies', 'like', "%{$query}%")
                ->take(10)->get();
        }

        return view('search', compact('query', 'posts', 'projects'));
    }
}
