<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    /**
     * GET /api/posts
     */
    public function index(Request $request): JsonResponse
    {
        $query = Post::published()
            ->with(['category:id,name,name_ar,slug', 'tags:id,name,name_ar,slug'])
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($builder) use ($request) {
                $builder->where('slug', (string) $request->string('category'));
            });
        }

        if ($request->filled('q')) {
            $term = '%'.$request->string('q').'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('title', 'like', $term)
                    ->orWhere('title_ar', 'like', $term)
                    ->orWhere('content', 'like', $term)
                    ->orWhere('content_ar', 'like', $term);
            });
        }

        $posts = $query->paginate(min((int) $request->integer('per_page', 12), 50));

        return response()->json([
            'data' => $posts->getCollection()->map(fn (Post $post) => $this->transform($post)),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'last_page' => $posts->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/posts/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        $post = Post::published()
            ->with(['category:id,name,name_ar,slug', 'tags:id,name,name_ar,slug'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['data' => $this->transform($post, true)]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function transform(Post $post, bool $withBody = false): array
    {
        $data = [
            'id' => $post->id,
            'title' => $post->title,
            'title_ar' => $post->title_ar,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'excerpt_ar' => $post->excerpt_ar,
            'featured_image' => $post->featured_image,
            'reading_time' => $post->reading_time,
            'published_at' => optional($post->published_at)->toIso8601String(),
            'views_count' => $post->views_count,
            'category' => $post->category ? [
                'name' => $post->category->name,
                'name_ar' => $post->category->name_ar,
                'slug' => $post->category->slug,
            ] : null,
            'tags' => $post->tags->map(fn ($tag) => [
                'name' => $tag->name,
                'name_ar' => $tag->name_ar,
                'slug' => $tag->slug,
            ])->values(),
            'url' => url('/blog/'.$post->slug),
        ];

        if ($withBody) {
            $data['content'] = $post->content;
            $data['content_ar'] = $post->content_ar;
            $data['html'] = markdown_html(post_body($post));
        }

        return $data;
    }
}
