<?php

namespace CafeHistoire\CafeBlog\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use CafeHistoire\CafeBlog\Models\Post;
use CafeHistoire\CafeBlog\Models\Category;

class PostApiController extends Controller
{
    /**
     * GET /api/blog/posts
     */
    public function index(Request $request): JsonResponse
    {
        $query = Post::published()->with(['author', 'category']);

        if ($request->filled('origin')) {
            $query->byOrigin($request->input('origin'));
        }
        if ($request->filled('era')) {
            $query->byEra($request->input('era'));
        }
        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->input('category')));
        }
        if ($request->boolean('featured')) {
            $query->featured();
        }

        $perPage = min((int) $request->input('per_page', 12), 50);
        $posts   = $query->latest()->paginate($perPage);

        return response()->json([
            'data'  => $posts->map(fn (Post $p) => $this->formatPost($p, false)),
            'meta'  => [
                'current_page' => $posts->currentPage(),
                'last_page'    => $posts->lastPage(),
                'per_page'     => $posts->perPage(),
                'total'        => $posts->total(),
            ],
        ]);
    }

    /**
     * GET /api/blog/posts/{slug}
     */
    public function show(Post $post): JsonResponse
    {
        abort_if(! $post->is_published, 404);
        $post->load(['author', 'category', 'tags']);

        return response()->json(['data' => $this->formatPost($post, true)]);
    }

    /**
     * GET /api/blog/categories
     */
    public function categories(): JsonResponse
    {
        $cats = Category::active()->withCount('publishedPosts')->get();

        return response()->json([
            'data' => $cats->map(fn ($c) => [
                'id'          => $c->id,
                'name'        => $c->name,
                'slug'        => $c->slug,
                'description' => $c->description,
                'color'       => $c->color,
                'icon'        => $c->icon,
                'posts_count' => $c->published_posts_count,
                'url'         => route('cafe-blog.categories.show', $c),
            ]),
        ]);
    }

    /**
     * GET /api/blog/origins
     */
    public function origins(): JsonResponse
    {
        $counts = Post::published()
            ->whereNotNull('coffee_origin')
            ->selectRaw('coffee_origin, count(*) as total')
            ->groupBy('coffee_origin')
            ->pluck('total', 'coffee_origin');

        $data = collect(Post::ORIGINS)->map(fn ($label, $key) => [
            'key'        => $key,
            'label'      => $label,
            'posts_count' => $counts[$key] ?? 0,
            'url'        => route('cafe-blog.origins.show', $key),
        ])->values();

        return response()->json(['data' => $data]);
    }

    /**
     * GET /api/blog/search?q=
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->input('q', '');

        if (strlen($q) < 2) {
            return response()->json(['data' => [], 'meta' => ['total' => 0]]);
        }

        $posts = Post::published()
            ->where(fn ($query) => $query
                ->where('title', 'like', "%{$q}%")
                ->orWhere('excerpt', 'like', "%{$q}%")
            )
            ->with(['author', 'category'])
            ->latest()
            ->paginate(12);

        return response()->json([
            'data' => $posts->map(fn ($p) => $this->formatPost($p, false)),
            'meta' => ['total' => $posts->total()],
        ]);
    }

    // ─── Private helper ───────────────────────────────────────────────────────

    private function formatPost(Post $post, bool $withContent): array
    {
        $data = [
            'id'              => $post->id,
            'title'           => $post->title,
            'slug'            => $post->slug,
            'excerpt'         => $post->excerpt,
            'featured_image'  => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
            'is_featured'     => $post->is_featured,
            'coffee_origin'   => $post->coffee_origin,
            'origin_label'    => $post->origin_label,
            'coffee_era'      => $post->coffee_era,
            'era_label'       => $post->era_label,
            'reading_time'    => $post->formatted_reading_time,
            'views_count'     => $post->views_count,
            'published_at'    => $post->published_at?->toIso8601String(),
            'url'             => route('cafe-blog.posts.show', $post),
            'author'          => $post->author ? [
                'name'   => $post->author->name,
                'slug'   => $post->author->slug,
                'avatar' => $post->author->avatar_url,
            ] : null,
            'category'        => $post->category ? [
                'name'  => $post->category->name,
                'slug'  => $post->category->slug,
                'color' => $post->category->color,
                'icon'  => $post->category->icon,
            ] : null,
        ];

        if ($withContent) {
            $data['content'] = $post->content;
            $data['tags']    = $post->tags->pluck('name');
            $data['meta']    = [
                'title'       => $post->meta_title,
                'description' => $post->meta_description,
                'keywords'    => $post->meta_keywords,
            ];
        }

        return $data;
    }
}
