@extends('cafe-blog::layouts.app')
@section('title', 'Articles — Administration CaféHistoire')

@push('styles')
<style>
.admin-page  { max-width: 1100px; margin: 2.5rem auto; padding-inline: var(--gutter); }
.admin-header{ display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem; }
.admin-title { font-family: var(--font-display); font-size: 1.75rem; color: var(--color-espresso); }
.filter-bar  { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.5rem; align-items: center; }
.admin-table { width: 100%; border-collapse: collapse; background: var(--color-white); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); }
.admin-table th { background: var(--color-espresso); color: var(--color-cream); font-family: var(--font-ui); font-size: .78rem; letter-spacing: .06em; text-transform: uppercase; padding: .75rem 1rem; text-align: left; }
.admin-table td { padding: .85rem 1rem; border-bottom: 1px solid var(--color-border); font-family: var(--font-ui); font-size: .88rem; vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }
.admin-table tr:hover td { background: #faf7f2; }
.status-badge { display: inline-block; padding: .2rem .55rem; border-radius: var(--radius-pill); font-size: .72rem; font-weight: 700; text-transform: uppercase; }
.status-badge--published { background: #EDF7ED; color: #1B5E20; }
.status-badge--draft     { background: #FFF8E1; color: #7B5800; }
.status-badge--scheduled { background: #E8F4FD; color: #0D47A1; }
.action-links { display: flex; gap: .5rem; }
.action-link  { font-size: .78rem; color: var(--color-roast); text-decoration: none; padding: .2rem .4rem; border-radius: var(--radius-sm); transition: background var(--transition); }
.action-link:hover { background: var(--color-cream); }
.action-link--danger { color: #C0392B; }
</style>
@endpush

@section('content')
<div class="admin-page">

    <div class="admin-header">
        <h1 class="admin-title">📝 Articles</h1>
        <a href="{{ route('cafe-blog.admin.posts.create') }}" class="cafe-btn cafe-btn--primary">+ Nouvel article</a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}" class="cafe-input" style="width:240px" placeholder="Rechercher un titre…">
        <select name="status" class="cafe-input" style="width:160px">
            <option value="">Tous les statuts</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publiés</option>
            <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Brouillons</option>
            <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Planifiés</option>
        </select>
        <button type="submit" class="cafe-btn cafe-btn--ghost cafe-btn--sm">Filtrer</button>
        @if(request()->hasAny(['search','status']))
        <a href="{{ route('cafe-blog.admin.posts.index') }}" class="cafe-btn cafe-btn--ghost cafe-btn--sm">✕ Réinitialiser</a>
        @endif
    </form>

    @if($posts->isEmpty())
    <div class="cafe-empty">Aucun article trouvé.</div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Auteur</th>
                <th>Statut</th>
                <th>Lectures</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td style="max-width:260px">
                    <strong style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $post->title }}
                    </strong>
                    @if($post->is_featured)<span style="font-size:.7rem;color:var(--color-caramel)">⭐ En avant</span>@endif
                </td>
                <td>
                    @if($post->category)
                    <span style="color:{{ $post->category->color }}">{{ $post->category->icon }} {{ $post->category->name }}</span>
                    @else
                    <span style="color:var(--color-text-muted)">—</span>
                    @endif
                </td>
                <td>{{ $post->author?->name }}</td>
                <td><span class="status-badge status-badge--{{ $post->status }}">{{ $post->status }}</span></td>
                <td>{{ number_format($post->views_count) }}</td>
                <td style="white-space:nowrap;font-size:.8rem;color:var(--color-text-muted)">
                    {{ $post->published_at?->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}
                </td>
                <td>
                    <div class="action-links">
                        <a href="{{ route('cafe-blog.admin.posts.edit', $post) }}" class="action-link">✏️ Modifier</a>
                        @if($post->is_published)
                        <a href="{{ route('cafe-blog.posts.show', $post) }}" target="_blank" class="action-link">🔗</a>
                        @endif
                        <form action="{{ route('cafe-blog.admin.posts.destroy', $post) }}" method="POST"
                              onsubmit="return confirm('Supprimer cet article ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-link action-link--danger" style="background:none;border:none;cursor:pointer">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="cafe-pagination" style="margin-top:1.5rem">{{ $posts->links('cafe-blog::partials.pagination') }}</div>
    @endif

</div>
@endsection
