<?php

namespace CafeHistoire\CafeBlog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use CafeHistoire\CafeBlog\Models\Category;

class CategoryAdminController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('publishedPosts')->orderBy('sort_order')->paginate(20);
        return view('cafe-blog::admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('cafe-blog::admin.categories.form', ['category' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request);
        Category::create($data);
        return redirect()->route('cafe-blog.admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(Category $category)
    {
        return view('cafe-blog::admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validate($request));
        return redirect()->route('cafe-blog.admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('cafe-blog.admin.categories.index')->with('success', 'Catégorie supprimée.');
    }

    private function validate(Request $request): array
    {
        return $request->validate([
            'name'             => ['required', 'string', 'max:150'],
            'description'      => ['nullable', 'string', 'max:500'],
            'color'            => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon'             => ['nullable', 'string', 'max:10'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'sort_order'       => ['integer', 'min:0'],
            'is_active'        => ['boolean'],
        ]);
    }
}
