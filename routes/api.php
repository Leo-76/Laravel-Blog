<?php

use Illuminate\Support\Facades\Route;
use CafeHistoire\CafeBlog\Http\Controllers\Api\PostApiController;

/*
|--------------------------------------------------------------------------
| CaféHistoire Blog — API Routes
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api/blog (via ServiceProvider config).
|
*/

Route::prefix('blog')->name('cafe-blog.api.')->middleware('throttle:60,1')->group(function () {

    // GET /api/blog/posts
    Route::get('/posts', [PostApiController::class, 'index'])->name('posts.index');

    // GET /api/blog/posts/{slug}
    Route::get('/posts/{post:slug}', [PostApiController::class, 'show'])->name('posts.show');

    // GET /api/blog/categories
    Route::get('/categories', [PostApiController::class, 'categories'])->name('categories');

    // GET /api/blog/origins
    Route::get('/origins', [PostApiController::class, 'origins'])->name('origins');

    // GET /api/blog/search?q=
    Route::get('/search', [PostApiController::class, 'search'])->name('search');

});
