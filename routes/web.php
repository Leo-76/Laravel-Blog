<?php

use Illuminate\Support\Facades\Route;
use CafeHistoire\CafeBlog\Http\Controllers\PostController;
use CafeHistoire\CafeBlog\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Blog Front-end Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PostController::class, 'index'])->name('cafe-blog.index');

Route::get('/recherche', [PostController::class, 'search'])->name('cafe-blog.search');

Route::get('/categorie/{category:slug}', [PostController::class, 'byCategory'])
    ->name('cafe-blog.categories.show');

Route::get('/origine/{origin}', [PostController::class, 'byOrigin'])
    ->name('cafe-blog.origins.show');

Route::get('/epoque/{era}', [PostController::class, 'byEra'])
    ->name('cafe-blog.eras.show');

Route::get('/auteur/{author:slug}', [PostController::class, 'byAuthor'])
    ->name('cafe-blog.authors.show');

Route::get('/feed.rss', [PostController::class, 'feed'])
    ->name('cafe-blog.feed');

Route::get('/sitemap.xml', [PostController::class, 'sitemap'])
    ->name('cafe-blog.sitemap');

Route::get('/{post:slug}', [PostController::class, 'show'])
    ->name('cafe-blog.posts.show')
    ->middleware('cafe-blog.track-views');

Route::post('/{post:slug}/commentaire', [PostController::class, 'storeComment'])
    ->name('cafe-blog.posts.comment')
    ->middleware('throttle:5,1');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
if (config('cafe-blog.admin_enabled', true)) {
    Route::prefix(config('cafe-blog.admin_prefix', 'admin'))
        ->name('cafe-blog.admin.')
        ->middleware(config('cafe-blog.admin_middleware', ['web', 'auth']))
        ->group(function () {
            Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

            Route::resource('posts', \CafeHistoire\CafeBlog\Http\Controllers\Admin\PostAdminController::class);
            Route::resource('categories', \CafeHistoire\CafeBlog\Http\Controllers\Admin\CategoryAdminController::class);
            Route::resource('authors', \CafeHistoire\CafeBlog\Http\Controllers\Admin\AuthorAdminController::class);
            Route::resource('comments', \CafeHistoire\CafeBlog\Http\Controllers\Admin\CommentAdminController::class)
                ->only(['index', 'update', 'destroy']);
        });
}
