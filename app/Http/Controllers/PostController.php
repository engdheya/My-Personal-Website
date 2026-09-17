<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->with(['category', 'tags']);

        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        $posts = $query->latest('published_at')->paginate(12);
        $categories = Category::withCount(['posts' => fn($q) => $q->published()])->get();
        $tags = Tag::all();

        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->when(!auth()->check(), fn($q) => $q->published())
            ->with(['category', 'tags', 'user'])
            ->firstOrFail();

        $post->increment('views_count');

        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->take(2)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
