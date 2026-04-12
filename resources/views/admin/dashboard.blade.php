@extends('cafe-blog::layouts.app')
@section('title', 'Administration — CaféHistoire Blog')

@push('styles')
<style>
.admin-wrap   { display: grid; grid-template-columns: 220px 1fr; min-height: calc(100vh - 64px); }
.admin-sidebar{ background: var(--color-espresso); padding: 1.5rem 0; }
.admin-nav a  { display: flex; align-items: center; gap: .6rem; padding: .65rem 1.5rem;
                color: rgba(255,255,255,.65); font-family: var(--font-ui); font-size: .88rem;
                text-decoration: none; transition: all .2s; }
.admin-nav a:hover, .admin-nav a.active { background: rgba(255,255,255,.08); color: var(--color-caramel); }
.admin-nav-sep{ border: none; border-top: 1px solid rgba(255,255,255,.1); margin: .75rem 0; }
.admin-main   { padding: 2.5rem var(--gutter); background: #f8f5f0; }
.admin-title  { font-family: var(--font-display); font-size: 1.75rem; color: var(--color-espresso); margin-bottom: 2rem; }
.stats-grid   { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2.5rem; }
.stat-card    { background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-lg);
                padding: 1.5rem; display: flex; flex-direction: column; gap: .4rem; }
.stat-value   { font-family: var(--font-display); font-size: 2.25rem; font-weight: 700; color: var(--color-roast); line-height: 1; }
.stat-label   { font-family: var(--font-ui); font-size: .82rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: .06em; }
.stat-card--alert .stat-value { color: #C0392B; }
.admin-grid   { display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.75rem; }
@media (max-width: 900px){ .admin-wrap { grid-template-columns: 1fr; } .admin-sidebar { display: none; } .admin-grid { grid-template-columns: 1fr; } }
.admin-table  { width: 100%; border-collapse: collapse; background: var(--color-white); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); }
.admin-table th { background: var(--color-espresso); color: var(--color-cream); font-family: var(--font-ui); font-size: .78rem; letter-spacing: .06em; text-transform: uppercase; padding: .75rem 1rem; text-align: left; }
.admin-table td { padding: .75rem 1rem; border-bottom: 1px solid var(--color-border); font-family: var(--font-ui); font-size: .88rem; vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }
.admin-table tr:hover td { background: var(--color-parchment); }
.status-badge { display: inline-block; padding: .2rem .55rem; border-radius: var(--radius-pill); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
.status-badge--published { background: #EDF7ED; color: #1B5E20; }
.status-badge--draft     { background: #FFF8E1; color: #7B5800; }
.status-badge--scheduled { background: #E8F4FD; color: #0D47A1; }
.admin-widget { background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-lg); overflow: hidden; }
.admin-widget__header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; }
.admin-widget__title  { font-family: var(--font-display); font-size: 1rem; color: var(--color-espresso); }
.admin-widget__body   { padding: 0; }
</style>
@endpush

@section('content')
<div class="admin-wrap">

    {{-- Admin Sidebar --}}
    <aside class="admin-sidebar">
        <nav class="admin-nav">
            <a href="{{ route('cafe-blog.admin.dashboard') }}" class="active">📊 Tableau de bord</a>
            <hr class="admin-nav-sep">
            <a href="{{ route('cafe-blog.admin.posts.index') }}">📝 Articles</a>
            <a href="{{ route('cafe-blog.admin.posts.create') }}">✏️ Nouvel article</a>
            <hr class="admin-nav-sep">
            <a href="{{ route('cafe-blog.admin.categories.index') }}">📂 Catégories</a>
            <a href="{{ route('cafe-blog.admin.authors.index') }}">👤 Auteurs</a>
            <a href="{{ route('cafe-blog.admin.comments.index') }}">
                💬 Commentaires
                @if($stats['comments_pending'] > 0)
                <span style="margin-left:auto;background:#C0392B;color:#fff;border-radius:999px;padding:.1rem .4rem;font-size:.72rem;font-weight:700">{{ $stats['comments_pending'] }}</span>
                @endif
            </a>
            <hr class="admin-nav-sep">
            <a href="{{ route('cafe-blog.index') }}" target="_blank">🔗 Voir le blog</a>
        </nav>
    </aside>

    {{-- Main content --}}
    <main class="admin-main">
        <h1 class="admin-title">☕ Tableau de bord</h1>

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-value">{{ number_format($stats['posts_published']) }}</span>
                <span class="stat-label">Articles publiés</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $stats['posts_draft'] }}</span>
                <span class="stat-label">Brouillons</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ number_format($stats['total_views']) }}</span>
                <span class="stat-label">Lectures totales</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $stats['categories'] }}</span>
                <span class="stat-label">Catégories</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">{{ $stats['authors'] }}</span>
                <span class="stat-label">Auteurs actifs</span>
            </div>
            <div class="stat-card {{ $stats['comments_pending'] > 0 ? 'stat-card--alert' : '' }}">
                <span class="stat-value">{{ $stats['comments_pending'] }}</span>
                <span class="stat-label">Commentaires en attente</span>
            </div>
        </div>

        <div class="admin-grid">

            {{-- Recent posts --}}
            <div class="admin-widget">
                <div class="admin-widget__header">
                    <h2 class="admin-widget__title">📝 Articles récents</h2>
                    <a href="{{ route('cafe-blog.admin.posts.create') }}" class="cafe-btn cafe-btn--primary cafe-btn--sm">+ Nouveau</a>
                </div>
                <div class="admin-widget__body">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Statut</th>
                                <th>Lectures</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPosts as $post)
                            <tr>
                                <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    <strong>{{ Str::limit($post->title, 45) }}</strong>
                                </td>
                                <td>{{ $post->author?->name }}</td>
                                <td>
                                    <span class="status-badge status-badge--{{ $post->status }}">
                                        {{ $post->status }}
                                    </span>
                                </td>
                                <td>{{ number_format($post->views_count) }}</td>
                                <td>
                                    <a href="{{ route('cafe-blog.admin.posts.edit', $post) }}" style="color:var(--color-roast);font-size:.8rem">✏️</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pending comments + popular posts --}}
            <div style="display:flex;flex-direction:column;gap:1.75rem">

                {{-- Popular posts --}}
                <div class="admin-widget">
                    <div class="admin-widget__header">
                        <h2 class="admin-widget__title">🔥 Articles populaires</h2>
                    </div>
                    <div class="admin-widget__body">
                        <table class="admin-table">
                            <thead><tr><th>Titre</th><th>Lectures</th></tr></thead>
                            <tbody>
                                @foreach($popularPosts as $post)
                                <tr>
                                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                        <a href="{{ route('cafe-blog.posts.show', $post) }}" target="_blank" style="color:var(--color-roast);text-decoration:none">
                                            {{ Str::limit($post->title, 40) }}
                                        </a>
                                    </td>
                                    <td><strong>{{ number_format($post->views_count) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pending comments --}}
                @if($pendingComments->isNotEmpty())
                <div class="admin-widget">
                    <div class="admin-widget__header">
                        <h2 class="admin-widget__title">💬 Commentaires en attente</h2>
                        <a href="{{ route('cafe-blog.admin.comments.index') }}" class="cafe-btn cafe-btn--ghost cafe-btn--sm">Tous voir</a>
                    </div>
                    <div class="admin-widget__body">
                        <table class="admin-table">
                            <thead><tr><th>Auteur</th><th>Article</th><th></th></tr></thead>
                            <tbody>
                                @foreach($pendingComments as $comment)
                                <tr>
                                    <td><strong>{{ $comment->author_name }}</strong></td>
                                    <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.8rem">{{ $comment->post?->title }}</td>
                                    <td>
                                        <form action="{{ route('cafe-blog.admin.comments.update', $comment) }}" method="POST" style="display:inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="cafe-btn cafe-btn--sm" style="background:#EDF7ED;color:#1B5E20;border:none;cursor:pointer;padding:.25rem .6rem">✓</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </main>
</div>
@endsection
