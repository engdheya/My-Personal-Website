<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $currentCategory = null;

        $query = Post::published()->with('category')->latest('published_at');

        if (is_string($categorySlug) && $categorySlug !== '') {
            $currentCategory = Category::where('slug', $categorySlug)->first();

            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
            } else {
                $categorySlug = null;
            }
        }

        // 12 articles per page (matches the SSR engine).
        $posts = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->withCount(['posts' => fn ($builder) => $builder->published()])
            ->orderBy('name')
            ->get();

        $tags = Tag::orderBy('name')->get();

        $breadcrumbs = [
            ['name' => __('site.nav_blog'), 'url' => '/blog'],
        ];

        if ($currentCategory) {
            $breadcrumbs[] = [
                'name' => loc_field($currentCategory, 'name'),
                'url' => '/blog?category='.$currentCategory->slug,
            ];
        }

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'currentCategory' => $currentCategory,
            'selectedCategorySlug' => $categorySlug,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::with(['category', 'tags', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Drafts & scheduled posts are only previewable by a signed-in admin.
        if (! $post->isPublished() && ! auth()->check()) {
            abort(404);
        }

        $post->increment('views_count');

        $body = post_body($post);
        $contentHtml = markdown_html($body);
        $toc = markdown_toc($body);

        $relatedPosts = Post::published()
            ->with('category')
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->take(2)
            ->get();

        $breadcrumbs = [
            ['name' => __('site.nav_blog'), 'url' => '/blog'],
        ];

        if ($post->category) {
            $breadcrumbs[] = [
                'name' => loc_field($post->category, 'name'),
                'url' => '/blog?category='.$post->category->slug,
            ];
        }

        $breadcrumbs[] = [
            'name' => loc_field($post, 'title'),
            'url' => '/blog/'.$post->slug,
        ];

        return view('blog.show', [
            'post' => $post,
            'contentHtml' => $contentHtml,
            'toc' => $toc,
            'postTags' => $post->tags,
            'relatedPosts' => $relatedPosts,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
