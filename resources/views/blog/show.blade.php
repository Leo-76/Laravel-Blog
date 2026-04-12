@extends('cafe-blog::layouts.app')

@section('title', ($post->meta_title ?? $post->title) . ' — ' . config('cafe-blog.site_name'))
@section('meta_description', $post->meta_description ?? $post->excerpt)
@if($post->meta_keywords)
@section('meta_keywords', $post->meta_keywords)
@endif
@section('og_type', 'article')
@if($post->featured_image)
@section('og_image', asset('storage/' . $post->featured_image))
@endif

@push('styles')
<style>
    .cafe-reading-progress { position: fixed; top: 0; left: 0; height: 3px; background: var(--color-caramel); z-index: 1000; transition: width 0.1s; }
</style>
@endpush

@section('content')

{{-- Reading progress bar --}}
<div class="cafe-reading-progress" id="readingProgress" role="progressbar" aria-label="Progression de lecture"></div>

{{-- Post Header --}}
<header class="cafe-post-header">
    <div class="cafe-container cafe-container--narrow">

        {{-- Breadcrumb --}}
        <nav class="cafe-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ route('cafe-blog.index') }}">Blog</a>
            @if($post->category)
            <span aria-hidden="true">›</span>
            <a href="{{ route('cafe-blog.categories.show', $post->category) }}">{{ $post->category->name }}</a>
            @endif
            <span aria-hidden="true">›</span>
            <span aria-current="page">{{ Str::limit($post->title, 40) }}</span>
        </nav>

        {{-- Metadata badges --}}
        <div class="cafe-post-badges">
            @if($post->category)
            <a href="{{ route('cafe-blog.categories.show', $post->category) }}"
               class="cafe-badge"
               style="--badge-color: {{ $post->category->color }}">
                {{ $post->category->icon }} {{ $post->category->name }}
            </a>
            @endif

            @if($post->coffee_origin)
            <a href="{{ route('cafe-blog.origins.show', $post->coffee_origin) }}" class="cafe-badge cafe-badge--origin">
                🌍 {{ $post->origin_label }}
            </a>
            @endif

            @if($post->coffee_era)
            <a href="{{ route('cafe-blog.eras.show', $post->coffee_era) }}" class="cafe-badge cafe-badge--era">
                📅 {{ $post->era_label }}
            </a>
            @endif
        </div>

        {{-- Title --}}
        <h1 class="cafe-post-header__title">{{ $post->title }}</h1>

        {{-- Excerpt --}}
        @if($post->excerpt)
        <p class="cafe-post-header__excerpt">{{ $post->excerpt }}</p>
        @endif

        {{-- Meta --}}
        <div class="cafe-post-header__meta cafe-meta">
            <a href="{{ route('cafe-blog.authors.show', $post->author) }}" class="cafe-meta__author-link">
                <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" class="cafe-meta__avatar cafe-meta__avatar--md">
                <div>
                    <strong class="cafe-meta__author">{{ $post->author->name }}</strong>
                    @if($post->author->coffee_preference)
                    <small class="cafe-meta__pref">☕ {{ $post->author->coffee_preference }}</small>
                    @endif
                </div>
            </a>
            <div class="cafe-meta__details">
                <time datetime="{{ $post->published_at->toDateString() }}">
                    {{ $post->published_at->translatedFormat('j F Y') }}
                </time>
                <span class="cafe-meta__sep">·</span>
                <span>{{ $post->formatted_reading_time }}</span>
                @if(config('cafe-blog.view_count_enabled'))
                <span class="cafe-meta__sep">·</span>
                <span>{{ number_format($post->views_count) }} lectures</span>
                @endif
            </div>
        </div>

    </div>
</header>

{{-- Featured Image --}}
@if($post->featured_image)
<div class="cafe-post-cover">
    <figure>
        <img
            src="{{ asset('storage/' . $post->featured_image) }}"
            alt="{{ $post->featured_image_caption ?? $post->title }}"
            class="cafe-post-cover__img"
            loading="eager"
        >
        @if($post->featured_image_caption)
        <figcaption class="cafe-post-cover__caption">{{ $post->featured_image_caption }}</figcaption>
        @endif
    </figure>
</div>
@endif

{{-- Post Body --}}
<div class="cafe-container cafe-container--narrow">
    <div class="cafe-post-layout">

        {{-- Article content --}}
        <article class="cafe-post-content" id="article-content">
            {!! $post->content !!}
        </article>

        {{-- Tags --}}
        @if($post->tags->isNotEmpty())
        <div class="cafe-post-tags">
            <span class="cafe-post-tags__label">🏷 Tags :</span>
            @foreach($post->tags as $tag)
            <a href="{{ route('cafe-blog.search', ['q' => $tag->name]) }}" class="cafe-tag">{{ $tag->name }}</a>
            @endforeach
        </div>
        @endif

        {{-- Social sharing --}}
        @if(config('cafe-blog.social_sharing_enabled'))
        <div class="cafe-share">
            <span class="cafe-share__label">Partager :</span>
            <div class="cafe-share__links">
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                   class="cafe-share__link cafe-share__link--twitter" target="_blank" rel="noopener noreferrer"
                   aria-label="Partager sur X (Twitter)">𝕏</a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                   class="cafe-share__link cafe-share__link--facebook" target="_blank" rel="noopener noreferrer"
                   aria-label="Partager sur Facebook">f</a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}"
                   class="cafe-share__link cafe-share__link--linkedin" target="_blank" rel="noopener noreferrer"
                   aria-label="Partager sur LinkedIn">in</a>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                   class="cafe-share__link cafe-share__link--whatsapp" target="_blank" rel="noopener noreferrer"
                   aria-label="Partager sur WhatsApp">📲</a>
            </div>
        </div>
        @endif

        {{-- Author bio --}}
        <div class="cafe-author-box">
            <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" class="cafe-author-box__avatar">
            <div class="cafe-author-box__content">
                <h3 class="cafe-author-box__name">
                    <a href="{{ route('cafe-blog.authors.show', $post->author) }}">{{ $post->author->name }}</a>
                </h3>
                @if($post->author->bio)
                <p class="cafe-author-box__bio">{{ $post->author->bio }}</p>
                @endif
                <a href="{{ route('cafe-blog.authors.show', $post->author) }}" class="cafe-btn cafe-btn--ghost cafe-btn--sm">
                    Tous ses articles →
                </a>
            </div>
        </div>

    </div>{{-- /.cafe-post-layout --}}
</div>{{-- /.cafe-container --}}

{{-- Related Posts --}}
@if($related->isNotEmpty())
<section class="cafe-related" aria-label="Articles similaires">
    <div class="cafe-container">
        <h2 class="cafe-section-title">☕ Pour continuer votre lecture</h2>
        <div class="cafe-grid cafe-grid--3">
            @foreach($related as $relatedPost)
                @include('cafe-blog::components.post-card', ['post' => $relatedPost, 'compact' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Comments --}}
@if(config('cafe-blog.comments_enabled'))
<section class="cafe-comments" id="comments" aria-label="Commentaires">
    <div class="cafe-container cafe-container--narrow">
        <h2 class="cafe-section-title">
            💬 {{ $post->approvedComments->count() }}
            {{ Str::plural('commentaire', $post->approvedComments->count()) }}
        </h2>

        {{-- List of approved comments --}}
        @foreach($post->approvedComments->whereNull('parent_id') as $comment)
        <div class="cafe-comment" id="comment-{{ $comment->id }}">
            <div class="cafe-comment__header">
                <strong class="cafe-comment__author">{{ e($comment->author_name) }}</strong>
                <time class="cafe-comment__date" datetime="{{ $comment->created_at->toDateString() }}">
                    {{ $comment->created_at->translatedFormat('j F Y') }}
                </time>
            </div>
            <div class="cafe-comment__body">{{ e($comment->content) }}</div>

            {{-- Replies --}}
            @foreach($comment->approvedReplies as $reply)
            <div class="cafe-comment cafe-comment--reply">
                <div class="cafe-comment__header">
                    <strong class="cafe-comment__author">{{ e($reply->author_name) }}</strong>
                    <time class="cafe-comment__date" datetime="{{ $reply->created_at->toDateString() }}">
                        {{ $reply->created_at->translatedFormat('j F Y') }}
                    </time>
                </div>
                <div class="cafe-comment__body">{{ e($reply->content) }}</div>
            </div>
            @endforeach
        </div>
        @endforeach

        {{-- Comment form --}}
        <div class="cafe-comment-form-wrap">
            <h3 class="cafe-comment-form__title">Laissez un commentaire</h3>
            <form action="{{ route('cafe-blog.posts.comment', $post) }}" method="POST" class="cafe-comment-form">
                @csrf
                <div class="cafe-form-row">
                    <div class="cafe-form-group">
                        <label for="author_name">Nom *</label>
                        <input type="text" id="author_name" name="author_name"
                               value="{{ old('author_name') }}" required
                               class="cafe-input @error('author_name') cafe-input--error @enderror">
                        @error('author_name')<span class="cafe-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="cafe-form-group">
                        <label for="author_email">E-mail * <small>(non publié)</small></label>
                        <input type="email" id="author_email" name="author_email"
                               value="{{ old('author_email') }}" required
                               class="cafe-input @error('author_email') cafe-input--error @enderror">
                        @error('author_email')<span class="cafe-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="cafe-form-group">
                    <label for="content">Votre commentaire *</label>
                    <textarea id="content" name="content" rows="5" required
                              class="cafe-textarea @error('content') cafe-input--error @enderror">{{ old('content') }}</textarea>
                    @error('content')<span class="cafe-field-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="cafe-btn cafe-btn--primary">
                    ☕ Publier le commentaire
                </button>
            </form>
        </div>

    </div>
</section>
@endif

@push('scripts')
<script>
// Reading progress bar
const progress = document.getElementById('readingProgress');
const article  = document.getElementById('article-content');
if (progress && article) {
    window.addEventListener('scroll', () => {
        const rect  = article.getBoundingClientRect();
        const total = article.offsetHeight - window.innerHeight;
        const done  = Math.max(0, -rect.top);
        progress.style.width = Math.min(100, (done / total) * 100) + '%';
    }, { passive: true });
}
</script>
@endpush

@endsection
