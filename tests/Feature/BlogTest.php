<?php

use CafeHistoire\CafeBlog\Models\Post;
use CafeHistoire\CafeBlog\Models\Author;
use CafeHistoire\CafeBlog\Models\Category;
use CafeHistoire\CafeBlog\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ─── Blog Index ───────────────────────────────────────────────────────────────

test('blog index is accessible', function () {
    $response = $this->get(route('cafe-blog.index'));
    $response->assertStatus(200);
    $response->assertViewIs('cafe-blog::blog.index');
});

test('blog index shows published posts', function () {
    $author   = Author::factory()->create();
    $category = Category::factory()->create();

    $published = Post::factory()->published()->create([
        'author_id'   => $author->id,
        'category_id' => $category->id,
    ]);
    $draft = Post::factory()->draft()->create([
        'author_id'   => $author->id,
        'category_id' => $category->id,
    ]);

    $response = $this->get(route('cafe-blog.index'));

    $response->assertSee($published->title);
    $response->assertDontSee($draft->title);
});

// ─── Post Show ────────────────────────────────────────────────────────────────

test('published post is accessible by slug', function () {
    $post = Post::factory()->published()->create();

    $response = $this->get(route('cafe-blog.posts.show', $post));

    $response->assertStatus(200);
    $response->assertSee($post->title);
});

test('draft post returns 404', function () {
    $post = Post::factory()->draft()->create();

    $response = $this->get(route('cafe-blog.posts.show', $post));

    $response->assertStatus(404);
});

test('post view count increments on first visit', function () {
    $post = Post::factory()->published()->create(['views_count' => 0]);

    $this->get(route('cafe-blog.posts.show', $post));

    expect($post->fresh()->views_count)->toBe(1);
});

test('post view count does not increment on repeated visit in same session', function () {
    $post = Post::factory()->published()->create(['views_count' => 0]);

    $this->get(route('cafe-blog.posts.show', $post));
    $this->get(route('cafe-blog.posts.show', $post));

    expect($post->fresh()->views_count)->toBe(1);
});

// ─── Category & Origin ────────────────────────────────────────────────────────

test('category page shows posts from that category', function () {
    $category = Category::factory()->create();
    $other    = Category::factory()->create();

    $post1 = Post::factory()->published()->create(['category_id' => $category->id]);
    $post2 = Post::factory()->published()->create(['category_id' => $other->id]);

    $response = $this->get(route('cafe-blog.categories.show', $category));

    $response->assertStatus(200);
    $response->assertSee($post1->title);
    $response->assertDontSee($post2->title);
});

test('origin page shows posts from that origin', function () {
    $post = Post::factory()->published()->create(['coffee_origin' => 'ethiopie']);

    $response = $this->get(route('cafe-blog.origins.show', 'ethiopie'));

    $response->assertStatus(200);
    $response->assertSee($post->title);
});

test('invalid origin returns 404', function () {
    $this->get(route('cafe-blog.origins.show', 'invalid-origin'))->assertStatus(404);
});

// ─── Search ───────────────────────────────────────────────────────────────────

test('search returns matching posts', function () {
    $post = Post::factory()->published()->create(['title' => 'Histoire unique du Yirgacheffe']);

    $response = $this->get(route('cafe-blog.search', ['q' => 'Yirgacheffe']));

    $response->assertStatus(200);
    $response->assertSee('Yirgacheffe');
});

test('search returns empty state for no results', function () {
    $response = $this->get(route('cafe-blog.search', ['q' => 'xxxxxxxxxnotfound']));

    $response->assertStatus(200);
});

// ─── Comments ─────────────────────────────────────────────────────────────────

test('valid comment can be submitted', function () {
    $post = Post::factory()->published()->create();

    $response = $this->post(route('cafe-blog.posts.comment', $post), [
        'author_name'  => 'Jean Dupont',
        'author_email' => 'jean@example.com',
        'content'      => 'Superbe article sur l\'histoire du café !',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('cafe_blog_comments', [
        'post_id'     => $post->id,
        'author_name' => 'Jean Dupont',
    ]);
});

test('comment with invalid data is rejected', function () {
    $post = Post::factory()->published()->create();

    $response = $this->post(route('cafe-blog.posts.comment', $post), [
        'author_name'  => '',
        'author_email' => 'not-an-email',
        'content'      => 'hi',
    ]);

    $response->assertSessionHasErrors(['author_name', 'author_email', 'content']);
});

// ─── RSS & Sitemap ────────────────────────────────────────────────────────────

test('rss feed is accessible and returns xml', function () {
    $response = $this->get(route('cafe-blog.feed'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/rss+xml; charset=UTF-8');
});

test('sitemap is accessible and returns xml', function () {
    $response = $this->get(route('cafe-blog.sitemap'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
});

// ─── API ──────────────────────────────────────────────────────────────────────

test('api posts endpoint returns json', function () {
    Post::factory()->published()->count(3)->create();

    $response = $this->getJson('/api/blog/posts');

    $response->assertStatus(200)
             ->assertJsonStructure(['data', 'meta']);
});

test('api post show returns correct post', function () {
    $post = Post::factory()->published()->create();

    $response = $this->getJson("/api/blog/posts/{$post->slug}");

    $response->assertStatus(200)
             ->assertJsonPath('data.slug', $post->slug);
});

test('api post show returns 404 for draft', function () {
    $post = Post::factory()->draft()->create();

    $this->getJson("/api/blog/posts/{$post->slug}")->assertStatus(404);
});
