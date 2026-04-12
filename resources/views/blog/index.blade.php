@extends('cafe-blog::layouts.app')

@section('title', __('cafe-blog::blog.index_title') . ' — ' . config('cafe-blog.site_name'))
@section('meta_description', config('cafe-blog.site_description'))

@section('content')

{{-- Hero / Featured Section --}}
@if($featured->isNotEmpty())
<section class="cafe-hero" aria-label="Articles à la une">
    <div class="cafe-hero__inner">

        {{-- Main featured post --}}
        @with($featured->first())
        <article class="cafe-hero__main">
            @if($featured->first()->featured_image)
            <div class="cafe-hero__image-wrap">
                <img
                    src="{{ asset('storage/' . $featured->first()->featured_image) }}"
                    alt="{{ $featured->first()->title }}"
                    class="cafe-hero__image"
                    loading="eager"
                >
            </div>
            @endif
            <div class="cafe-hero__content">
                @if($featured->first()->category)
                <a href="{{ route('cafe-blog.categories.show', $featured->first()->category) }}"
                   class="cafe-badge"
                   style="--badge-color: {{ $featured->first()->category->color }}">
                    {{ $featured->first()->category->icon }} {{ $featured->first()->category->name }}
                </a>
                @endif

                <h1 class="cafe-hero__title">
                    <a href="{{ route('cafe-blog.posts.show', $featured->first()) }}">
                        {{ $featured->first()->title }}
                    </a>
                </h1>

                <p class="cafe-hero__excerpt">{{ $featured->first()->excerpt }}</p>

                <div class="cafe-meta">
                    <img src="{{ $featured->first()->author->avatar_url }}" alt="{{ $featured->first()->author->name }}" class="cafe-meta__avatar">
                    <span class="cafe-meta__author">{{ $featured->first()->author->name }}</span>
                    <span class="cafe-meta__sep">·</span>
                    <time class="cafe-meta__date" datetime="{{ $featured->first()->published_at->toDateString() }}">
                        {{ $featured->first()->published_at->translatedFormat('j F Y') }}
                    </time>
                    <span class="cafe-meta__sep">·</span>
                    <span class="cafe-meta__read">{{ $featured->first()->formatted_reading_time }}</span>
                </div>
            </div>
        </article>
        @endwith

        {{-- Secondary featured posts --}}
        @if($featured->count() > 1)
        <div class="cafe-hero__secondary">
            @foreach($featured->skip(1) as $post)
            <article class="cafe-hero__card">
                @if($post->featured_image)
                <a href="{{ route('cafe-blog.posts.show', $post) }}" class="cafe-hero__card-img-wrap">
                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                         alt="{{ $post->title }}"
                         class="cafe-hero__card-img"
                         loading="lazy">
                </a>
                @endif
                <div class="cafe-hero__card-body">
                    <h2 class="cafe-hero__card-title">
                        <a href="{{ route('cafe-blog.posts.show', $post) }}">{{ $post->title }}</a>
                    </h2>
                    <time class="cafe-meta__date" datetime="{{ $post->published_at->toDateString() }}">
                        {{ $post->published_at->translatedFormat('j F Y') }}
                    </time>
                </div>
            </article>
            @endforeach
        </div>
        @endif

    </div>
</section>

{{-- Decorative divider --}}
<div class="cafe-divider" aria-hidden="true">
    <svg viewBox="0 0 1200 40" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 20 Q300 0 600 20 Q900 40 1200 20" stroke="currentColor" stroke-width="1.5" fill="none" opacity="0.3"/>
        <circle cx="600" cy="20" r="5" fill="currentColor" opacity="0.5"/>
        <circle cx="580" cy="20" r="3" fill="currentColor" opacity="0.3"/>
        <circle cx="620" cy="20" r="3" fill="currentColor" opacity="0.3"/>
    </svg>
</div>
@endif

{{-- Filter Bar: Origins --}}
<section class="cafe-filters" aria-label="Filtrer par origine">
    <div class="cafe-container">
        <div class="cafe-filters__inner">
            <span class="cafe-filters__label">🌍 Origines :</span>
            <div class="cafe-filters__list">
                <a href="{{ route('cafe-blog.index') }}"
                   class="cafe-filter-pill {{ !request()->route('origin') ? 'cafe-filter-pill--active' : '' }}">
                    Tous
                </a>
                @foreach($origins as $key => $label)
                <a href="{{ route('cafe-blog.origins.show', $key) }}"
                   class="cafe-filter-pill {{ request()->route('origin') === $key ? 'cafe-filter-pill--active' : '' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Main content + Sidebar --}}
<div class="cafe-container cafe-layout">

    {{-- Posts Grid --}}
    <section class="cafe-posts" aria-label="Tous les articles">

        @if($posts->isEmpty())
        <div class="cafe-empty">
            <p>☕ Aucun article pour le moment. Revenez bientôt !</p>
        </div>
        @else
        <div class="cafe-grid">
            @foreach($posts as $post)
                @include('cafe-blog::components.post-card', ['post' => $post])
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="cafe-pagination">
            {{ $posts->links('cafe-blog::partials.pagination') }}
        </div>
        @endif

    </section>

    {{-- Sidebar --}}
    <aside class="cafe-sidebar" aria-label="Sidebar">
        @include('cafe-blog::partials.sidebar', [
            'categories' => $categories,
            'popular'    => $popular,
        ])
    </aside>

</div>

@endsection
