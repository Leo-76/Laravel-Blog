<?php

namespace CafeHistoire\CafeBlog\Services;

use CafeHistoire\CafeBlog\Models\Post;
use CafeHistoire\CafeBlog\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CafeBlogService
{
    public function __construct(protected array $config = [])
    {
    }

    /**
     * Get paginated latest published posts.
     */
    public function latestPosts(int $perPage = 12): LengthAwarePaginator
    {
        return Post::published()
            ->with(['author', 'category'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get featured posts.
     */
    public function featuredPosts(int $limit = 5): Collection
    {
        return Post::published()
            ->featured()
            ->with(['author', 'category'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get most popular posts.
     */
    public function popularPosts(int $limit = 5): Collection
    {
        return Post::published()
            ->popular()
            ->with('author')
            ->limit($limit)
            ->get();
    }

    /**
     * Get posts by origin.
     */
    public function postsByOrigin(string $origin, int $perPage = 12): LengthAwarePaginator
    {
        return Post::published()
            ->byOrigin($origin)
            ->with(['author', 'category'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all active categories with post counts.
     */
    public function allCategories(): Collection
    {
        return Category::active()
            ->withCount('publishedPosts')
            ->get();
    }

    /**
     * Get all coffee origins with post counts.
     */
    public function coffeeOriginsWithCounts(): array
    {
        $counts = Post::published()
            ->whereNotNull('coffee_origin')
            ->selectRaw('coffee_origin, count(*) as total')
            ->groupBy('coffee_origin')
            ->pluck('total', 'coffee_origin')
            ->toArray();

        return collect(Post::ORIGINS)
            ->map(fn ($label, $key) => [
                'key'   => $key,
                'label' => $label,
                'count' => $counts[$key] ?? 0,
            ])
            ->filter(fn ($o) => $o['count'] > 0)
            ->values()
            ->toArray();
    }

    /**
     * Get all defined coffee origins.
     */
    public function coffeeOrigins(): array
    {
        return Post::ORIGINS;
    }

    /**
     * Format a reading time for display.
     */
    public function formatReadingTime(int $minutes): string
    {
        if ($minutes <= 1) {
            return __('cafe-blog::blog.reading_time_one');
        }
        return __('cafe-blog::blog.reading_time_many', ['minutes' => $minutes]);
    }

    /**
     * Calculate reading time from content string.
     */
    public function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, (int) ceil($wordCount / 200));
    }
}
