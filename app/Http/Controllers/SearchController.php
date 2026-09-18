<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $matchingPosts = collect();
        $matchingProjects = collect();

        if ($query !== '') {
            $term = '%'.$query.'%';

            $matchingPosts = Post::published()
                ->with('category')
                ->where(function ($builder) use ($term) {
                    $builder->where('title', 'like', $term)
                        ->orWhere('title_ar', 'like', $term)
                        ->orWhere('content', 'like', $term)
                        ->orWhere('content_ar', 'like', $term);
                })
                ->latest('published_at')
                ->take(20)
                ->get();

            $matchingProjects = Project::query()
                ->where(function ($builder) use ($term) {
                    $builder->where('title', 'like', $term)
                        ->orWhere('title_ar', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('technologies', 'like', $term);
                })
                ->latest('id')
                ->take(10)
                ->get();
        }

        $breadcrumbs = [
            ['name' => __('site.search_title'), 'url' => '/search'],
        ];

        return view('search', [
            'query' => $query,
            'matchingPosts' => $matchingPosts,
            'matchingProjects' => $matchingProjects,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
