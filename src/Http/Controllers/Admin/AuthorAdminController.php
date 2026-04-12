<?php

namespace CafeHistoire\CafeBlog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use CafeHistoire\CafeBlog\Models\Author;

class AuthorAdminController extends Controller
{
    public function index()
    {
        $authors = Author::withCount('posts')->orderBy('name')->paginate(20);
        return view('cafe-blog::admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('cafe-blog::admin.authors.form', ['author' => null]);
    }

    public function store(Request $request)
    {
        Author::create($this->validateAuthor($request));
        return redirect()->route('cafe-blog.admin.authors.index')->with('success', 'Auteur créé.');
    }

    public function edit(Author $author)
    {
        return view('cafe-blog::admin.authors.form', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $author->update($this->validateAuthor($request, $author));
        return redirect()->route('cafe-blog.admin.authors.index')->with('success', 'Auteur mis à jour.');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('cafe-blog.admin.authors.index')->with('success', 'Auteur supprimé.');
    }

    private function validateAuthor(Request $request, ?Author $author = null): array
    {
        return $request->validate([
            'name'              => ['required', 'string', 'max:150'],
            'email'             => ['required', 'email', 'unique:cafe_blog_authors,email' . ($author ? ",{$author->id}" : '')],
            'bio'               => ['nullable', 'string', 'max:1000'],
            'avatar'            => ['nullable', 'image', 'max:2048'],
            'website'           => ['nullable', 'url', 'max:200'],
            'twitter'           => ['nullable', 'string', 'max:100'],
            'instagram'         => ['nullable', 'string', 'max:100'],
            'coffee_preference' => ['nullable', 'string', 'max:200'],
            'origin_specialty'  => ['nullable', 'string', 'max:100'],
            'is_active'         => ['boolean'],
        ]);
    }
}
