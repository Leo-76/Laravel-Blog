<?php

namespace CafeHistoire\CafeBlog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use CafeHistoire\CafeBlog\Models\Post;

class TrackPostViews
{
    /**
     * Track post views, throttled per session per post.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! config('cafe-blog.view_count_enabled', true)) {
            return $response;
        }

        $post = $request->route('post');

        if ($post instanceof Post && $request->isMethod('GET') && $response->getStatusCode() === 200) {
            $sessionKey = 'cafe_blog_viewed_' . $post->id;

            if (! $request->session()->has($sessionKey)) {
                $request->session()->put($sessionKey, true);
                $post->incrementViews();
            }
        }

        return $response;
    }
}
