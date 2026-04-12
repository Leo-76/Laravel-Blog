{{-- era.blade.php --}}
@extends('cafe-blog::layouts.app')
@section('title', '📅 ' . $eraLabel . ' — ' . config('cafe-blog.site_name'))
@section('content')
<div class="cafe-container" style="padding-block:3rem">
    <header style="margin-bottom:2.5rem;padding-bottom:1.5rem;border-bottom:2px solid #5C4A7A">
        <h1 class="cafe-section-title">📅 {{ $eraLabel }}</h1>
        <p style="color:var(--color-text-muted);font-family:var(--font-ui)">Articles sur l'histoire du café à l'époque : <strong>{{ $eraLabel }}</strong></p>
    </header>
    @if($posts->isEmpty())
    <div class="cafe-empty"><p>Aucun article pour cette époque pour le moment.</p></div>
    @else
    <div class="cafe-grid">@foreach($posts as $post)@include('cafe-blog::components.post-card',['post'=>$post])@endforeach</div>
    <div class="cafe-pagination">{{ $posts->links('cafe-blog::partials.pagination') }}</div>
    @endif
</div>
@endsection

