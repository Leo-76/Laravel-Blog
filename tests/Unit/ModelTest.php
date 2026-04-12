<?php

use CafeHistoire\CafeBlog\Models\Post;
use CafeHistoire\CafeBlog\Models\Author;
use CafeHistoire\CafeBlog\Models\Category;
use CafeHistoire\CafeBlog\Services\CafeBlogService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ─── Post Model ───────────────────────────────────────────────────────────────

test('post origin_label accessor returns translated label', function () {
    $post = new Post(['coffee_origin' => 'ethiopie']);
    expect($post->origin_label)->toBe('Éthiopie');
});

test('post era_label accessor returns translated label', function () {
    $post = new Post(['coffee_era' => 'moderne']);
    expect($post->era_label)->toBe('Époque Moderne (1700–1900)');
});

test('post calculates reading time correctly', function () {
    $words   = implode(' ', array_fill(0, 400, 'café')); // 400 words
    $post    = new Post(['content' => $words]);
    $post->calculateReadingTime();

    expect($post->reading_time_minutes)->toBe(2); // 400 / 200 = 2 min
});

test('formatted reading time is correct for 1 minute', function () {
    $post = new Post(['reading_time_minutes' => 1]);
    expect($post->formatted_reading_time)->toBe('1 min de lecture');
});

test('post is_new returns true for recent post', function () {
    $post = new Post(['published_at' => now()->subDays(3)]);
    expect($post->isNew())->toBeTrue();
});

test('post is_new returns false for old post', function () {
    $post = new Post(['published_at' => now()->subDays(10)]);
    expect($post->isNew())->toBeFalse();
});

test('post slug is auto-generated from title', function () {
    $author   = Author::factory()->create();
    $category = Category::factory()->create();

    $post = Post::create([
        'title'        => 'L\'histoire du café en Éthiopie',
        'content'      => 'Contenu de test pour l\'article.',
        'author_id'    => $author->id,
        'category_id'  => $category->id,
        'status'       => 'draft',
    ]);

    expect($post->slug)->toBe('lhistoire-du-cafe-en-ethiopie');
});

// ─── Author Model ─────────────────────────────────────────────────────────────

test('author avatar url falls back to gravatar when no avatar set', function () {
    $author = new Author(['email' => 'test@example.com', 'avatar' => null]);
    expect($author->avatar_url)->toContain('gravatar.com');
});

// ─── Category Model ───────────────────────────────────────────────────────────

test('category has expected default categories list', function () {
    expect(Category::DEFAULT_CATEGORIES)->toBeArray()->not->toBeEmpty();
    expect(Category::DEFAULT_CATEGORIES[0])->toHaveKey('name');
});

// ─── CafeBlogService ──────────────────────────────────────────────────────────

test('service calculates reading time correctly', function () {
    $service = new CafeBlogService();
    $content = implode(' ', array_fill(0, 600, 'word')); // 600 words → 3 min

    expect($service->calculateReadingTime($content))->toBe(3);
});

test('service returns all coffee origins', function () {
    $service  = new CafeBlogService();
    $origins  = $service->coffeeOrigins();

    expect($origins)->toBeArray()->toHaveKey('ethiopie');
});

// ─── Post Scopes ──────────────────────────────────────────────────────────────

test('published scope excludes draft posts', function () {
    Post::factory()->published()->create();
    Post::factory()->draft()->create();

    $results = Post::published()->get();

    expect($results)->toHaveCount(1);
});

test('featured scope only returns featured posts', function () {
    Post::factory()->published()->featured()->create();
    Post::factory()->published()->create(['is_featured' => false]);

    $results = Post::published()->featured()->get();

    expect($results)->toHaveCount(1);
});

test('byOrigin scope filters correctly', function () {
    Post::factory()->published()->create(['coffee_origin' => 'ethiopie']);
    Post::factory()->published()->create(['coffee_origin' => 'bresil']);

    $results = Post::published()->byOrigin('ethiopie')->get();

    expect($results)->toHaveCount(1);
    expect($results->first()->coffee_origin)->toBe('ethiopie');
});
