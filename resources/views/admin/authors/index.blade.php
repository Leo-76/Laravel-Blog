@extends('cafe-blog::layouts.app')
@section('title', 'Auteurs — Administration CaféHistoire')

@push('styles')
<style>
.admin-page  { max-width: 960px; margin: 2.5rem auto; padding-inline: var(--gutter); }
.admin-header{ display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; }
.admin-title { font-family: var(--font-display); font-size: 1.75rem; color: var(--color-espresso); }
.admin-table { width: 100%; border-collapse: collapse; background: var(--color-white); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); }
.admin-table th { background: var(--color-espresso); color: var(--color-cream); font-family: var(--font-ui); font-size: .78rem; letter-spacing: .06em; text-transform: uppercase; padding: .75rem 1rem; text-align: left; }
.admin-table td { padding: .85rem 1rem; border-bottom: 1px solid var(--color-border); font-family: var(--font-ui); font-size: .88rem; vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }
.admin-table tr:hover td { background: #faf7f2; }
.action-link { font-size: .78rem; color: var(--color-roast); text-decoration: none; padding: .2rem .45rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); background: var(--color-white); cursor: pointer; margin-right: .25rem; }
.action-link:hover { background: var(--color-cream); }
.action-link--delete { color: #C0392B; border-color: #F5C6C6; }
</style>
@endpush

@section('content')
<div class="admin-page">

    <div class="admin-header">
        <h1 class="admin-title">👤 Auteurs</h1>
        <a href="{{ route('cafe-blog.admin.authors.create') }}" class="cafe-btn cafe-btn--primary">+ Nouvel auteur</a>
    </div>

    @if($authors->isEmpty())
    <div class="cafe-empty"><p>Aucun auteur. <a href="{{ route('cafe-blog.admin.authors.create') }}">Créez le premier</a>.</p></div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Préférence café</th>
                <th>Spécialité origine</th>
                <th>Articles</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($authors as $author)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:.65rem">
                        <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}"
                             style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--color-border)">
                        <div>
                            <strong>{{ $author->name }}</strong><br>
                            <a href="{{ route('cafe-blog.authors.show', $author) }}" target="_blank"
                               style="font-size:.75rem;color:var(--color-text-muted)">Voir le profil</a>
                        </div>
                    </div>
                </td>
                <td style="font-size:.82rem;color:var(--color-text-muted)">{{ $author->coffee_preference ?? '—' }}</td>
                <td style="font-size:.82rem">
                    @if($author->origin_specialty && isset(\CafeHistoire\CafeBlog\Models\Post::ORIGINS[$author->origin_specialty]))
                        🌍 {{ \CafeHistoire\CafeBlog\Models\Post::ORIGINS[$author->origin_specialty] }}
                    @else
                        <span style="color:var(--color-text-muted)">—</span>
                    @endif
                </td>
                <td>{{ $author->posts_count }}</td>
                <td>{{ $author->is_active ? '✅' : '❌' }}</td>
                <td>
                    <a href="{{ route('cafe-blog.admin.authors.edit', $author) }}" class="action-link">✏️ Modifier</a>
                    <form action="{{ route('cafe-blog.admin.authors.destroy', $author) }}" method="POST" style="display:inline"
                          onsubmit="return confirm('Supprimer {{ $author->name }} ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-link action-link--delete">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="cafe-pagination" style="margin-top:1.5rem">{{ $authors->links('cafe-blog::partials.pagination') }}</div>
    @endif

</div>
@endsection
