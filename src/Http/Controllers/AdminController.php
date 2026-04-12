<?php

namespace CafeHistoire\CafeBlog\Http\Controllers;

use Illuminate\Routing\Controller;
use CafeHistoire\CafeBlog\Models\Post;
use CafeHistoire\CafeBlog\Models\Category;
use CafeHistoire\CafeBlog\Models\Author;
use CafeHistoire\CafeBlog\Models\Comment;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'posts_published' => Post::where('status', Post::STATUS_PUBLISHED)->count(),
            'posts_draft'     => Post::where('status', Post::STATUS_DRAFT)->count(),
            'categories'      => Category::count(),
            'authors'         => Author::where('is_active', true)->count(),
            'comments_pending' => Comment::where('status', Comment::STATUS_PENDING)->count(),
            'total_views'     => Post::sum('views_count'),
        ];

        $recentPosts    = Post::with(['author', 'category'])->latest()->limit(8)->get();
        $pendingComments = Comment::with('post')->where('status', Comment::STATUS_PENDING)->latest()->limit(5)->get();
        $popularPosts   = Post::published()->popular()->with('author')->limit(5)->get();

        return view('cafe-blog::admin.dashboard', compact(
            'stats', 'recentPosts', 'pendingComments', 'popularPosts'
        ));
    }
}
