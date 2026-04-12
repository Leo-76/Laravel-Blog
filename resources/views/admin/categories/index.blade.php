@extends('cafe-blog::layouts.app')
@section('title', 'Catégories — Administration CaféHistoire')

@push('styles')
<style>
.admin-page  { max-width: 900px; margin: 2.5rem auto; padding-inline: var(--gutter); }
.admin-header{ display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; }
.admin-title { font-family: var(--font-display); font-size: 1.75rem; color: var(--color-espresso); }
.admin-table { width: 100%; border-collapse: collapse; background: var(--color-white); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); }
.admin-table th { background: var(--color-espresso); color: var(--color-cream); font-family: var(--font-ui); font-size: .78rem; letter-spacing: .06em; text-transform: uppercase; padding: .75rem 1rem; text-align: left; }
.admin-table td { padding: .85rem 1rem; border-bottom: 1px solid var(--color-border); font-family: var(--font-ui); font-size: .88rem; vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }
.admin-table tr:hover td { background: #faf7f2; }
.color-dot { display: inline-block; width: 14px; height: 14px; border-radius: 50%; border: 2px solid rgba(0,0,0,.15); vertical-align: middle; margin-right: .4rem; }
.action-link { font-size: .78rem; color: var(--color-roast); text-decoration: none; padding: .2rem .45rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); background: var(--color-white); cursor: pointer; margin-right: .25rem; }
.action-link:hover { background: var(--color-cream); }
.action-link--delete { color: #C0392B; border-color: #F5C6C6; }
</style>
@endpush

@section('content')
<div class="admin-page">

    <div class="admin-header">
        <h1 class="admin-title">📂 Catégories</h1>
        <a href="{{ route('cafe-blog.admin.categories.create') }}" class="cafe-btn cafe-btn--primary">+ Nouvelle catégorie</a>
    </div>

    @if($categories->isEmpty())
    <div class="cafe-empty">
        <p>Aucune catégorie. <a href="{{ route('cafe-blog.admin.categories.create') }}">Créez la première</a>.</p>
    </div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Slug</th>
                <th>Articles</th>
                <th>Ordre</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td>
                    <span class="color-dot" style="background:{{ $cat->color }}"></span>
                    <strong>{{ $cat->icon }} {{ $cat->name }}</strong>
                </td>
                <td style="color:var(--color-text-muted);font-size:.82rem">{{ $cat->slug }}</td>
                <td>{{ $cat->published_posts_count }}</td>
                <td>{{ $cat->sort_order }}</td>
                <td>{{ $cat->is_active ? '✅' : '❌' }}</td>
                <td>
                    <a href="{{ route('cafe-blog.admin.categories.edit', $cat) }}" class="action-link">✏️ Modifier</a>
                    <form action="{{ route('cafe-blog.admin.categories.destroy', $cat) }}" method="POST" style="display:inline"
                          onsubmit="return confirm('Supprimer « {{ $cat->name }} » ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-link action-link--delete">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="cafe-pagination" style="margin-top:1.5rem">{{ $categories->links('cafe-blog::partials.pagination') }}</div>
    @endif

</div>
@endsection
