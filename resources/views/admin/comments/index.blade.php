@extends('cafe-blog::layouts.app')
@section('title', 'Commentaires — Administration CaféHistoire')

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
.status-badge--approved { background: #EDF7ED; color: #1B5E20; }
.status-badge--pending  { background: #FFF8E1; color: #7B5800; }
.status-badge--spam     { background: #FCE4EC; color: #880E4F; }
.status-badge--trash    { background: #FAFAFA; color: #616161; }
.action-links { display: flex; gap: .4rem; flex-wrap: wrap; }
.action-link  { font-size: .78rem; color: var(--color-roast); text-decoration: none; padding: .2rem .45rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); cursor: pointer; background: var(--color-white); transition: background var(--transition); }
.action-link:hover { background: var(--color-cream); }
.action-link--approve { color: #1B5E20; border-color: #B8DEB5; }
.action-link--spam    { color: #880E4F; border-color: #F48FB1; }
.action-link--delete  { color: #C0392B; border-color: #F5C6C6; }
</style>
@endpush

@section('content')
<div class="admin-page">

    <div class="admin-header">
        <h1 class="admin-title">💬 Commentaires</h1>
    </div>

    {{-- Filter tabs --}}
    <form method="GET" class="filter-bar">
        <select name="status" class="cafe-input" style="width:180px">
            <option value="">Tous les statuts</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>⏳ En attente</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Approuvés</option>
            <option value="spam"     {{ request('status') === 'spam'     ? 'selected' : '' }}>🚫 Spam</option>
            <option value="trash"    {{ request('status') === 'trash'    ? 'selected' : '' }}>🗑️ Corbeille</option>
        </select>
        <button type="submit" class="cafe-btn cafe-btn--ghost cafe-btn--sm">Filtrer</button>
        @if(request('status'))
        <a href="{{ route('cafe-blog.admin.comments.index') }}" class="cafe-btn cafe-btn--ghost cafe-btn--sm">✕ Réinitialiser</a>
        @endif
    </form>

    @if($comments->isEmpty())
    <div class="cafe-empty">Aucun commentaire trouvé.</div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Auteur</th>
                <th>Commentaire</th>
                <th>Article</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
            <tr>
                <td style="white-space:nowrap">
                    <strong>{{ e($comment->author_name) }}</strong>
                    @if($comment->parent_id)
                    <br><span style="font-size:.72rem;color:var(--color-text-muted)">↳ Réponse</span>
                    @endif
                </td>
                <td style="max-width:300px">
                    <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:.85rem">
                        {{ e($comment->content) }}
                    </span>
                </td>
                <td style="max-width:180px">
                    @if($comment->post)
                    <a href="{{ route('cafe-blog.posts.show', $comment->post) }}" target="_blank"
                       style="color:var(--color-roast);text-decoration:none;font-size:.82rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                        {{ $comment->post->title }}
                    </a>
                    @else
                    <span style="color:var(--color-text-muted)">—</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge status-badge--{{ $comment->status }}">{{ $comment->status }}</span>
                </td>
                <td style="white-space:nowrap;font-size:.8rem;color:var(--color-text-muted)">
                    {{ $comment->created_at->format('d/m/Y H:i') }}
                </td>
                <td>
                    <div class="action-links">
                        {{-- Approve --}}
                        @if($comment->status !== 'approved')
                        <form action="{{ route('cafe-blog.admin.comments.update', $comment) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="action-link action-link--approve">✓ Approuver</button>
                        </form>
                        @endif

                        {{-- Mark as Spam --}}
                        @if($comment->status !== 'spam')
                        <form action="{{ route('cafe-blog.admin.comments.update', $comment) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="spam">
                            <button type="submit" class="action-link action-link--spam">🚫 Spam</button>
                        </form>
                        @endif

                        {{-- Delete --}}
                        <form action="{{ route('cafe-blog.admin.comments.destroy', $comment) }}" method="POST"
                              onsubmit="return confirm('Supprimer définitivement ce commentaire ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-link action-link--delete">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="cafe-pagination" style="margin-top:1.5rem">{{ $comments->links('cafe-blog::partials.pagination') }}</div>
    @endif

</div>
@endsection
