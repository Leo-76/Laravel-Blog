<?php

namespace CafeHistoire\CafeBlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use CafeHistoire\CafeBlog\Models\Post;
use CafeHistoire\CafeBlog\Models\Category;
use CafeHistoire\CafeBlog\Models\Author;
use CafeHistoire\CafeBlog\Services\CafeBlogService;
use CafeHistoire\CafeBlog\Http\Requests\CommentRequest;

class PostController extends Controller
{
    public function __construct(protected CafeBlogService $blogService)
    {
    }

    /**
     * Blog homepage — latest posts, featured, categories.
     */
    public function index(Request $request)
    {
        $featured   = Post::published()->featured()->with(['author', 'category'])->limit(3)->get();
        $posts      = Post::published()->with(['author', 'category'])->latest()->paginate(
            config('cafe-blog.posts_per_page', 12)
        );
        $categories = Category::active()->withCount('publishedPosts')->get();
        $popular    = Post::published()->popular()->with('author')->limit(5)->get();
        $origins    = Post::ORIGINS;

        return view('cafe-blog::blog.index', compact('featured', 'posts', 'categories', 'popular', 'origins'));
    }

    /**
     * Single post view.
     */
    public function show(Request $request, Post $post)
    {
        abort_if(! $post->is_published, 404);

        $post->load(['author', 'category', 'approvedComments.approvedReplies', 'relatedPosts']);
        $post->incrementViews();

        $related = $post->relatedPosts->count() >= 3
            ? $post->relatedPosts
            : Post::published()
                ->where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->with('author')
                ->limit(3)
                ->get();

        return view('cafe-blog::blog.show', compact('post', 'related'));
    }

    /**
     * Posts filtered by category.
     */
    public function byCategory(Category $category)
    {
        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['author', 'category'])
            ->latest()
            ->paginate(config('cafe-blog.posts_per_page', 12));

        return view('cafe-blog::blog.category', compact('category', 'posts'));
    }

    /**
     * Posts filtered by coffee origin.
     */
    public function byOrigin(Request $request, string $origin)
    {
        abort_unless(array_key_exists($origin, Post::ORIGINS), 404);

        $posts = Post::published()
            ->byOrigin($origin)
            ->with(['author', 'category'])
            ->latest()
            ->paginate(config('cafe-blog.posts_per_page', 12));

        $originLabel = Post::ORIGINS[$origin];

        return view('cafe-blog::blog.origin', compact('posts', 'origin', 'originLabel'));
    }

    /**
     * Posts filtered by historical era.
     */
    public function byEra(Request $request, string $era)
    {
        abort_unless(array_key_exists($era, Post::ERAS), 404);

        $posts = Post::published()
            ->byEra($era)
            ->with(['author', 'category'])
            ->latest()
            ->paginate(config('cafe-blog.posts_per_page', 12));

        $eraLabel = Post::ERAS[$era];

        return view('cafe-blog::blog.era', compact('posts', 'era', 'eraLabel'));
    }

    /**
     * Author profile and their posts.
     */
    public function byAuthor(Author $author)
    {
        $posts = $author->publishedPosts()
            ->with('category')
            ->latest()
            ->paginate(config('cafe-blog.posts_per_page', 12));

        return view('cafe-blog::blog.author', compact('author', 'posts'));
    }

    /**
     * Full-text search.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $posts = Post::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->with(['author', 'category'])
            ->latest()
            ->paginate(config('cafe-blog.posts_per_page', 12));

        return view('cafe-blog::blog.search', compact('posts', 'query'));
    }

    /**
     * Store a new comment on a post.
     */
    public function storeComment(CommentRequest $request, Post $post)
    {
        abort_if(! config('cafe-blog.comments_enabled', true), 403);

        $comment = $post->comments()->create([
            'author_name'    => $request->validated('author_name'),
            'author_email'   => $request->validated('author_email'),
            'author_website' => $request->validated('author_website'),
            'content'        => $request->validated('content'),
            'parent_id'      => $request->validated('parent_id'),
            'status'         => config('cafe-blog.auto_approve_comments', false) ? 'approved' : 'pending',
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        $message = $comment->isApproved()
            ? __('cafe-blog::blog.comment_published')
            : __('cafe-blog::blog.comment_pending');

        return redirect()
            ->to(route('cafe-blog.posts.show', $post) . '#comments')
            ->with('success', $message);
    }

    /**
     * RSS feed.
     */
    public function feed()
    {
        $posts = Post::published()
            ->with(['author', 'category'])
            ->latest()
            ->limit(50)
            ->get();

        return response()
            ->view('cafe-blog::blog.feed', compact('posts'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    /**
     * Sitemap.
     */
    public function sitemap()
    {
        $posts      = Post::published()->latest()->get();
        $categories = Category::active()->get();

        return response()
            ->view('cafe-blog::blog.sitemap', compact('posts', 'categories'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
