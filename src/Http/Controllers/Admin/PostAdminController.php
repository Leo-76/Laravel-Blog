<?php

namespace CafeHistoire\CafeBlog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use CafeHistoire\CafeBlog\Models\Post;
use CafeHistoire\CafeBlog\Models\Category;
use CafeHistoire\CafeBlog\Models\Author;
use CafeHistoire\CafeBlog\Services\CafeBlogService;
use Illuminate\Support\Facades\Storage;

class PostAdminController extends Controller
{
    public function __construct(protected CafeBlogService $blogService)
    {
    }

    public function index(Request $request)
    {
        $posts = Post::with(['author', 'category'])
            ->when($request->input('status'), fn ($q, $s) => $q->where('status', $s))
            ->when($request->input('search'), fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->latest()
            ->paginate(20);

        return view('cafe-blog::admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $authors    = Author::where('is_active', true)->get();

        return view('cafe-blog::admin.posts.form', [
            'post'       => null,
            'categories' => $categories,
            'authors'    => $authors,
            'origins'    => Post::ORIGINS,
            'eras'       => Post::ERAS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePost($request);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('cafe-blog/images', config('cafe-blog.disk', 'public'));
        }

        $post = Post::create($validated);
        $post->calculateReadingTime();
        $post->save();

        if ($request->filled('tags')) {
            $post->syncTags(array_map('trim', explode(',', $request->input('tags'))));
        }

        return redirect()
            ->route('cafe-blog.admin.posts.edit', $post)
            ->with('success', 'Article créé avec succès.');
    }

    public function edit(Post $post)
    {
        $post->load(['author', 'category', 'tags']);
        $categories = Category::active()->get();
        $authors    = Author::where('is_active', true)->get();

        return view('cafe-blog::admin.posts.form', [
            'post'       => $post,
            'categories' => $categories,
            'authors'    => $authors,
            'origins'    => Post::ORIGINS,
            'eras'       => Post::ERAS,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $this->validatePost($request, $post);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk(config('cafe-blog.disk', 'public'))->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('cafe-blog/images', config('cafe-blog.disk', 'public'));
        }

        $post->update($validated);
        $post->calculateReadingTime();
        $post->save();

        if ($request->filled('tags')) {
            $post->syncTags(array_map('trim', explode(',', $request->input('tags'))));
        }

        return redirect()
            ->route('cafe-blog.admin.posts.edit', $post)
            ->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('cafe-blog.admin.posts.index')
            ->with('success', 'Article supprimé.');
    }

    private function validatePost(Request $request, ?Post $post = null): array
    {
        return $request->validate([
            'title'                   => ['required', 'string', 'min:3', 'max:255'],
            'excerpt'                 => ['nullable', 'string', 'max:500'],
            'content'                 => ['required', 'string'],
            'featured_image'          => ['nullable', 'image', 'max:5120'],
            'featured_image_caption'  => ['nullable', 'string', 'max:255'],
            'author_id'               => ['required', 'exists:cafe_blog_authors,id'],
            'category_id'             => ['nullable', 'exists:cafe_blog_categories,id'],
            'status'                  => ['required', 'in:draft,published,scheduled'],
            'is_featured'             => ['boolean'],
            'coffee_origin'           => ['nullable', 'string', 'max:100'],
            'coffee_variety'          => ['nullable', 'string', 'max:100'],
            'coffee_era'              => ['nullable', 'string', 'max:100'],
            'brewing_method'          => ['nullable', 'string', 'max:100'],
            'meta_title'              => ['nullable', 'string', 'max:255'],
            'meta_description'        => ['nullable', 'string', 'max:300'],
            'meta_keywords'           => ['nullable', 'string', 'max:255'],
            'published_at'            => ['nullable', 'date'],
        ]);
    }
}
