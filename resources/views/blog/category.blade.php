{{-- category.blade.php --}}
@extends('cafe-blog::layouts.app')
@section('title', __('cafe-blog::blog.category_title', ['name' => $category->name]) . ' — ' . config('cafe-blog.site_name'))
@section('content')
<div class="cafe-container" style="padding-block:3rem">
    <div class="cafe-listing-header" style="margin-bottom:2.5rem;padding-bottom:1.5rem;border-bottom:2px solid var(--color-caramel)">
        <span class="cafe-badge" style="--badge-color:{{ $category->color }};font-size:1rem;padding:.4rem 1rem;">{{ $category->icon }} {{ $category->name }}</span>
        @if($category->description)
        <p style="margin-top:1rem;color:var(--color-text-muted);max-width:60ch">{{ $category->description }}</p>
        @endif
    </div>
    @if($posts->isEmpty())
    <div class="cafe-empty"><p>Aucun article dans cette catégorie pour le moment.</p></div>
    @else
    <div class="cafe-grid">@foreach($posts as $post)@include('cafe-blog::components.post-card',['post'=>$post])@endforeach</div>
    <div class="cafe-pagination">{{ $posts->links('cafe-blog::partials.pagination') }}</div>
    @endif
</div>
@endsection
