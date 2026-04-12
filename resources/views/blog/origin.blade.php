{{-- origin.blade.php --}}
@extends('cafe-blog::layouts.app')
@section('title', '🌍 ' . $originLabel . ' — ' . config('cafe-blog.site_name'))
@section('content')
<div class="cafe-container" style="padding-block:3rem">
    <header style="margin-bottom:2.5rem;padding-bottom:1.5rem;border-bottom:2px solid var(--color-leaf)">
        <h1 class="cafe-section-title">🌍 {{ $originLabel }}</h1>
        <p style="color:var(--color-text-muted);font-family:var(--font-ui)">Tous les articles sur le café d'origine <strong>{{ $originLabel }}</strong></p>
    </header>
    @if($posts->isEmpty())
    <div class="cafe-empty"><p>Aucun article pour cette origine pour le moment.</p></div>
    @else
    <div class="cafe-grid">@foreach($posts as $post)@include('cafe-blog::components.post-card',['post'=>$post])@endforeach</div>
    <div class="cafe-pagination">{{ $posts->links('cafe-blog::partials.pagination') }}</div>
    @endif
</div>
@endsection
