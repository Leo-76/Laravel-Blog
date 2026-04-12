@extends('cafe-blog::layouts.app')
@section('title', $post ? 'Modifier : ' . $post->title : 'Nouvel article')

@push('styles')
<style>
.admin-form-wrap { max-width: 900px; margin: 2.5rem auto; padding-inline: var(--gutter); }
.admin-form-title { font-family: var(--font-display); font-size: 1.75rem; color: var(--color-espresso); margin-bottom: 2rem; }
.form-section { background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.5rem; }
.form-section__title { font-family: var(--font-display); font-size: 1rem; color: var(--color-roast); margin-bottom: 1.25rem; padding-bottom: .5rem; border-bottom: 1px solid var(--color-border); }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
@media (max-width: 640px) { .form-grid-2 { grid-template-columns: 1fr; } }
.field { display: flex; flex-direction: column; gap: .35rem; margin-bottom: 1.1rem; }
.field:last-child { margin-bottom: 0; }
.field label { font-family: var(--font-ui); font-size: .85rem; font-weight: 600; color: var(--color-text); }
.field label .hint { font-weight: 400; color: var(--color-text-muted); margin-left: .35rem; font-size: .78rem; }
.char-count { font-family: var(--font-ui); font-size: .75rem; color: var(--color-text-muted); text-align: right; }
.form-actions { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
.form-actions .spacer { margin-left: auto; }
</style>
@endpush

@section('content')
<div class="admin-form-wrap">

    <div style="margin-bottom:1.5rem">
        <a href="{{ route('cafe-blog.admin.posts.index') }}" style="color:var(--color-roast);font-family:var(--font-ui);font-size:.88rem;text-decoration:none">
            ← Retour aux articles
        </a>
    </div>

    <h1 class="admin-form-title">
        {{ $post ? '✏️ Modifier l\'article' : '✏️ Nouvel article' }}
    </h1>

    <form
        action="{{ $post ? route('cafe-blog.admin.posts.update', $post) : route('cafe-blog.admin.posts.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @if($post) @method('PUT') @endif

        {{-- Main content --}}
        <div class="form-section">
            <h2 class="form-section__title">Contenu principal</h2>

            <div class="field">
                <label for="title">Titre *</label>
                <input type="text" id="title" name="title" class="cafe-input @error('title') cafe-input--error @enderror"
                       value="{{ old('title', $post?->title) }}" required maxlength="255"
                       placeholder="Un titre accrocheur pour cet article…">
                @error('title')<span class="cafe-field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="excerpt">Extrait <span class="hint">(résumé affiché dans les listes)</span></label>
                <textarea id="excerpt" name="excerpt" rows="3" maxlength="500"
                          class="cafe-textarea @error('excerpt') cafe-input--error @enderror"
                          placeholder="Résumez l'article en 2–3 phrases…">{{ old('excerpt', $post?->excerpt) }}</textarea>
                <div class="char-count"><span id="excerpt-count">{{ strlen(old('excerpt', $post?->excerpt ?? '')) }}</span>/500</div>
                @error('excerpt')<span class="cafe-field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="content">Contenu * <span class="hint">(HTML accepté)</span></label>
                <textarea id="content" name="content" rows="20"
                          class="cafe-textarea @error('content') cafe-input--error @enderror"
                          placeholder="Rédigez votre article ici…" required>{{ old('content', $post?->content) }}</textarea>
                @error('content')<span class="cafe-field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="tags">Tags <span class="hint">(séparés par des virgules)</span></label>
                <input type="text" id="tags" name="tags" class="cafe-input"
                       value="{{ old('tags', $post?->tags->pluck('name')->implode(', ')) }}"
                       placeholder="histoire, éthiopie, torréfaction, …">
            </div>
        </div>

        {{-- Image + metadata --}}
        <div class="form-section">
            <h2 class="form-section__title">Image & Métadonnées</h2>
            <div class="form-grid-2">

                <div>
                    <div class="field">
                        <label for="featured_image">Image principale</label>
                        @if($post?->featured_image)
                        <div style="margin-bottom:.75rem">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt=""
                                 style="max-height:120px;border-radius:var(--radius-md);border:1px solid var(--color-border)">
                        </div>
                        @endif
                        <input type="file" id="featured_image" name="featured_image" accept="image/*" class="cafe-input">
                        @error('featured_image')<span class="cafe-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="featured_image_caption">Légende de l'image</label>
                        <input type="text" id="featured_image_caption" name="featured_image_caption" class="cafe-input"
                               value="{{ old('featured_image_caption', $post?->featured_image_caption) }}"
                               placeholder="Source ou description…">
                    </div>
                </div>

                <div>
                    <div class="field">
                        <label for="author_id">Auteur *</label>
                        <select id="author_id" name="author_id" class="cafe-input" required>
                            <option value="">— Choisir un auteur —</option>
                            @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $post?->author_id) == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('author_id')<span class="cafe-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="category_id">Catégorie</label>
                        <select id="category_id" name="category_id" class="cafe-input">
                            <option value="">— Sans catégorie —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $post?->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Coffee metadata --}}
        <div class="form-section">
            <h2 class="form-section__title">🌍 Métadonnées café</h2>
            <div class="form-grid-2">
                <div class="field">
                    <label for="coffee_origin">Origine</label>
                    <select id="coffee_origin" name="coffee_origin" class="cafe-input">
                        <option value="">— Aucune origine spécifique —</option>
                        @foreach($origins as $key => $label)
                        <option value="{{ $key }}" {{ old('coffee_origin', $post?->coffee_origin) === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="coffee_era">Époque historique</label>
                    <select id="coffee_era" name="coffee_era" class="cafe-input">
                        <option value="">— Aucune époque spécifique —</option>
                        @foreach($eras as $key => $label)
                        <option value="{{ $key }}" {{ old('coffee_era', $post?->coffee_era) === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="coffee_variety">Variété de café</label>
                    <input type="text" id="coffee_variety" name="coffee_variety" class="cafe-input"
                           value="{{ old('coffee_variety', $post?->coffee_variety) }}"
                           placeholder="Arabica, Typica, Gesha, Robusta…">
                </div>
                <div class="field">
                    <label for="brewing_method">Méthode de préparation</label>
                    <input type="text" id="brewing_method" name="brewing_method" class="cafe-input"
                           value="{{ old('brewing_method', $post?->brewing_method) }}"
                           placeholder="Espresso, V60, Chemex, Cézve…">
                </div>
            </div>
        </div>

        {{-- Publication --}}
        <div class="form-section">
            <h2 class="form-section__title">📅 Publication</h2>
            <div class="form-grid-2">
                <div class="field">
                    <label for="status">Statut *</label>
                    <select id="status" name="status" class="cafe-input" required>
                        <option value="draft"     {{ old('status', $post?->status) === 'draft'     ? 'selected' : '' }}>📝 Brouillon</option>
                        <option value="published" {{ old('status', $post?->status) === 'published' ? 'selected' : '' }}>✅ Publié</option>
                        <option value="scheduled" {{ old('status', $post?->status) === 'scheduled' ? 'selected' : '' }}>📅 Planifié</option>
                    </select>
                </div>
                <div class="field">
                    <label for="published_at">Date de publication</label>
                    <input type="datetime-local" id="published_at" name="published_at" class="cafe-input"
                           value="{{ old('published_at', $post?->published_at?->format('Y-m-d\TH:i')) }}">
                </div>
            </div>
            <div class="field" style="flex-direction:row;align-items:center;gap:.75rem;margin-top:.5rem">
                <input type="checkbox" id="is_featured" name="is_featured" value="1"
                       {{ old('is_featured', $post?->is_featured) ? 'checked' : '' }}
                       style="width:18px;height:18px;accent-color:var(--color-roast)">
                <label for="is_featured" style="margin:0;font-weight:500">⭐ Article mis en avant (hero)</label>
            </div>
        </div>

        {{-- SEO --}}
        <div class="form-section">
            <h2 class="form-section__title">🔍 SEO</h2>
            <div class="field">
                <label for="meta_title">Titre SEO <span class="hint">(laissez vide pour utiliser le titre)</span></label>
                <input type="text" id="meta_title" name="meta_title" class="cafe-input" maxlength="255"
                       value="{{ old('meta_title', $post?->meta_title) }}"
                       placeholder="Titre pour les moteurs de recherche…">
            </div>
            <div class="field">
                <label for="meta_description">Description SEO <span class="hint">(150–160 caractères recommandés)</span></label>
                <textarea id="meta_description" name="meta_description" rows="2" maxlength="300"
                          class="cafe-textarea">{{ old('meta_description', $post?->meta_description) }}</textarea>
            </div>
            <div class="field">
                <label for="meta_keywords">Mots-clés <span class="hint">(séparés par des virgules)</span></label>
                <input type="text" id="meta_keywords" name="meta_keywords" class="cafe-input"
                       value="{{ old('meta_keywords', $post?->meta_keywords) }}"
                       placeholder="café, histoire, éthiopie, torréfaction…">
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <a href="{{ route('cafe-blog.admin.posts.index') }}" class="cafe-btn cafe-btn--ghost">Annuler</a>
            <div class="spacer"></div>
            @if($post)
            <a href="{{ route('cafe-blog.posts.show', $post) }}" target="_blank" class="cafe-btn cafe-btn--ghost cafe-btn--sm">
                🔗 Voir en ligne
            </a>
            <button type="submit" name="status_override" value="draft" class="cafe-btn cafe-btn--ghost">
                💾 Enregistrer brouillon
            </button>
            @endif
            <button type="submit" class="cafe-btn cafe-btn--primary">
                {{ $post ? '✅ Mettre à jour' : '✅ Créer l\'article' }}
            </button>
        </div>

    </form>
</div>
@push('scripts')
<script>
// Excerpt char counter
const excerpt = document.getElementById('excerpt');
const count   = document.getElementById('excerpt-count');
if (excerpt && count) {
    excerpt.addEventListener('input', () => { count.textContent = excerpt.value.length; });
}
</script>
@endpush
@endsection
