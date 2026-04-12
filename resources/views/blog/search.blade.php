@extends('cafe-blog::layouts.app')

@section('title', __('cafe-blog::blog.search_title', ['query' => $query]) . ' — ' . config('cafe-blog.site_name'))

@section('content')
<div class="cafe-container" style="padding-block: 3rem;">

    <header style="margin-bottom: 2rem;">
        <a href="{{ route('cafe-blog.index') }}" class="cafe-breadcrumb" style="display:inline-flex;gap:.5rem;font-family:var(--font-ui);font-size:.85rem;color:var(--color-text-muted);text-decoration:none;margin-bottom:1rem;">
            ← Retour au blog
        </a>
        <h1 class="cafe-section-title">
            🔍 Résultats pour « {{ $query }} »
        </h1>
        <p style="color:var(--color-text-muted);font-family:var(--font-ui);">
            {{ $posts->total() }} {{ Str::plural('article', $posts->total()) }} trouvé{{ $posts->total() > 1 ? 's' : '' }}
        </p>
    </header>

    @if($posts->isEmpty())
    <div class="cafe-empty">
        <p>Aucun article ne correspond à « {{ e($query) }} ».</p>
        <p>Essayez un autre mot-clé ou parcourez nos <a href="{{ route('cafe-blog.index') }}">derniers articles</a>.</p>
    </div>
    @else
    <div class="cafe-grid">
        @foreach($posts as $post)
            @include('cafe-blog::components.post-card', ['post' => $post])
        @endforeach
    </div>
    <div class="cafe-pagination">{{ $posts->appends(['q' => $query])->links('cafe-blog::partials.pagination') }}</div>
    @endif

</div>
@endsection
