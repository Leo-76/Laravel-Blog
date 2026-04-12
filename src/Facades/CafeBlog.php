<?php

namespace CafeHistoire\CafeBlog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator latestPosts(int $perPage = 12)
 * @method static \Illuminate\Database\Eloquent\Collection featuredPosts(int $limit = 5)
 * @method static \Illuminate\Database\Eloquent\Collection popularPosts(int $limit = 5)
 * @method static \Illuminate\Database\Eloquent\Collection postsByOrigin(string $origin, int $perPage = 12)
 * @method static \Illuminate\Database\Eloquent\Collection allCategories()
 * @method static array coffeeOrigins()
 * @method static string formatReadingTime(int $minutes)
 *
 * @see \CafeHistoire\CafeBlog\Services\CafeBlogService
 */
class CafeBlog extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cafe-blog';
    }
}
