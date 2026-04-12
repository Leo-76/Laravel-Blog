@extends('cafe-blog::layouts.app')
@section('title', $author->name . ' — ' . config('cafe-blog.site_name'))

@section('content')
<div class="cafe-container" style="padding-block:3rem">

    {{-- Author profile card --}}
    <div class="cafe-author-box" style="margin-bottom:3rem">
        <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="cafe-author-box__avatar" style="width:96px;height:96px">
        <div class="cafe-author-box__content">
            <h1 class="cafe-author-box__name" style="font-size:1.75rem">{{ $author->name }}</h1>
            @if($author->bio)
            <p class="cafe-author-box__bio">{{ $author->bio }}</p>
            @endif
            <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-top:.5rem">
                @if($author->coffee_preference)
                <span class="cafe-pill">☕ {{ $author->coffee_preference }}</span>
                @endif
                @if($author->origin_specialty && isset(\CafeHistoire\CafeBlog\Models\Post::ORIGINS[$author->origin_specialty]))
                <a href="{{ route('cafe-blog.origins.show', $author->origin_specialty) }}" class="cafe-pill cafe-pill--origin">
                    🌍 {{ \CafeHistoire\CafeBlog\Models\Post::ORIGINS[$author->origin_specialty] }}
                </a>
                @endif
                @if($author->twitter)
                <a href="https://twitter.com/{{ ltrim($author->twitter, '@') }}" target="_blank" rel="noopener" class="cafe-pill">𝕏 {{ $author->twitter }}</a>
                @endif
                @if($author->website)
                <a href="{{ $author->website }}" target="_blank" rel="noopener" class="cafe-pill">🔗 Site web</a>
                @endif
            </div>
        </div>
    </div>

    <h2 class="cafe-section-title">Articles publiés ({{ $posts->total() }})</h2>

    @if($posts->isEmpty())
    <div class="cafe-empty"><p>Cet auteur n'a pas encore publié d'articles.</p></div>
    @else
    <div class="cafe-grid">
        @foreach($posts as $post)
            @include('cafe-blog::components.post-card', ['post' => $post])
        @endforeach
    </div>
    <div class="cafe-pagination">{{ $posts->links('cafe-blog::partials.pagination') }}</div>
    @endif

</div>
@endsection
