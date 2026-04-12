{{-- Post Card Component
     Usage: @include('cafe-blog::components.post-card', ['post' => $post, 'compact' => false])
--}}
@php $compact = $compact ?? false; @endphp

<article class="cafe-card {{ $compact ? 'cafe-card--compact' : '' }}" itemscope itemtype="https://schema.org/Article">
    {{-- Image --}}
    @if($post->featured_image)
    <a href="{{ route('cafe-blog.posts.show', $post) }}" class="cafe-card__img-link" tabindex="-1" aria-hidden="true">
        <div class="cafe-card__img-wrap">
            <img
                src="{{ asset('storage/' . $post->featured_image) }}"
                alt="{{ $post->title }}"
                class="cafe-card__img"
                loading="lazy"
                itemprop="image"
            >
            @if($post->isNew())
            <span class="cafe-card__new-badge">Nouveau</span>
            @endif
        </div>
    </a>
    @endif

    {{-- Body --}}
    <div class="cafe-card__body">

        {{-- Category badge --}}
        @if($post->category && !$compact)
        <a href="{{ route('cafe-blog.categories.show', $post->category) }}"
           class="cafe-badge cafe-badge--sm"
           style="--badge-color: {{ $post->category->color }}">
            {{ $post->category->icon }} {{ $post->category->name }}
        </a>
        @endif

        {{-- Origin + Era (small pills) --}}
        @if($post->coffee_origin || $post->coffee_era)
        <div class="cafe-card__meta-pills">
            @if($post->coffee_origin)
            <span class="cafe-pill cafe-pill--origin">🌍 {{ $post->origin_label }}</span>
            @endif
            @if($post->coffee_era && !$compact)
            <span class="cafe-pill cafe-pill--era">📅 {{ $post->era_label }}</span>
            @endif
        </div>
        @endif

        {{-- Title --}}
        <h2 class="cafe-card__title" itemprop="headline">
            <a href="{{ route('cafe-blog.posts.show', $post) }}" itemprop="url">
                {{ $post->title }}
            </a>
        </h2>

        {{-- Excerpt --}}
        @if(!$compact && $post->excerpt)
        <p class="cafe-card__excerpt" itemprop="description">
            {{ Str::limit($post->excerpt, 140) }}
        </p>
        @endif

        {{-- Footer meta --}}
        <footer class="cafe-card__footer cafe-meta">
            <img src="{{ $post->author->avatar_url }}"
                 alt="{{ $post->author->name }}"
                 class="cafe-meta__avatar cafe-meta__avatar--sm"
                 itemprop="author">
            <span class="cafe-meta__author" itemprop="author">{{ $post->author->name }}</span>
            <span class="cafe-meta__sep">·</span>
            <time class="cafe-meta__date" datetime="{{ $post->published_at->toDateString() }}" itemprop="datePublished">
                {{ $post->published_at->translatedFormat('j M Y') }}
            </time>
            @if(config('cafe-blog.reading_time_enabled') && !$compact)
            <span class="cafe-meta__sep">·</span>
            <span class="cafe-meta__read">{{ $post->formatted_reading_time }}</span>
            @endif
        </footer>

    </div>{{-- /.cafe-card__body --}}
</article>
