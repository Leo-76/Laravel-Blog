@extends('cafe-blog::layouts.app')
@section('title', $category ? 'Modifier la catégorie' : 'Nouvelle catégorie')

@section('content')
<div style="max-width:640px;margin:2.5rem auto;padding-inline:var(--gutter)">

    <a href="{{ route('cafe-blog.admin.categories.index') }}" style="color:var(--color-roast);font-family:var(--font-ui);font-size:.88rem;text-decoration:none;display:block;margin-bottom:1.5rem">
        ← Retour aux catégories
    </a>

    <h1 style="font-family:var(--font-display);font-size:1.75rem;color:var(--color-espresso);margin-bottom:2rem">
        {{ $category ? '✏️ Modifier la catégorie' : '📂 Nouvelle catégorie' }}
    </h1>

    <form action="{{ $category ? route('cafe-blog.admin.categories.update', $category) : route('cafe-blog.admin.categories.store') }}"
          method="POST" style="display:flex;flex-direction:column;gap:1.25rem">
        @csrf
        @if($category) @method('PUT') @endif

        <div class="cafe-form-group">
            <label for="name">Nom de la catégorie *</label>
            <input type="text" id="name" name="name" class="cafe-input @error('name') cafe-input--error @enderror"
                   value="{{ old('name', $category?->name) }}" required>
            @error('name')<span class="cafe-field-error">{{ $message }}</span>@enderror
        </div>

        <div class="cafe-form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3" class="cafe-textarea">{{ old('description', $category?->description) }}</textarea>
        </div>

        <div class="cafe-form-row">
            <div class="cafe-form-group">
                <label for="color">Couleur</label>
                <div style="display:flex;align-items:center;gap:.75rem">
                    <input type="color" id="color" name="color"
                           value="{{ old('color', $category?->color ?? '#6B3A2A') }}"
                           style="width:50px;height:40px;border:1px solid var(--color-border);border-radius:var(--radius-sm);cursor:pointer;padding:2px">
                    <input type="text" name="color_text" id="color_text"
                           value="{{ old('color', $category?->color ?? '#6B3A2A') }}"
                           class="cafe-input" style="max-width:120px" placeholder="#6B3A2A">
                </div>
            </div>
            <div class="cafe-form-group">
                <label for="icon">Icône (emoji)</label>
                <input type="text" id="icon" name="icon" class="cafe-input"
                       value="{{ old('icon', $category?->icon) }}"
                       placeholder="☕" maxlength="10" style="max-width:100px;font-size:1.25rem;text-align:center">
            </div>
        </div>

        <div class="cafe-form-row">
            <div class="cafe-form-group">
                <label for="sort_order">Ordre d'affichage</label>
                <input type="number" id="sort_order" name="sort_order" class="cafe-input"
                       value="{{ old('sort_order', $category?->sort_order ?? 0) }}" min="0" max="999">
            </div>
            <div class="cafe-form-group" style="justify-content:flex-end">
                <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;margin-top:1.5rem">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }}
                           style="width:18px;height:18px;accent-color:var(--color-roast)">
                    Catégorie active
                </label>
            </div>
        </div>

        <div class="cafe-form-group">
            <label for="meta_title">Titre SEO</label>
            <input type="text" id="meta_title" name="meta_title" class="cafe-input"
                   value="{{ old('meta_title', $category?->meta_title) }}" maxlength="255">
        </div>
        <div class="cafe-form-group">
            <label for="meta_description">Description SEO</label>
            <textarea id="meta_description" name="meta_description" rows="2" class="cafe-textarea" maxlength="300">{{ old('meta_description', $category?->meta_description) }}</textarea>
        </div>

        <div style="display:flex;gap:1rem;margin-top:.5rem">
            <a href="{{ route('cafe-blog.admin.categories.index') }}" class="cafe-btn cafe-btn--ghost">Annuler</a>
            <button type="submit" class="cafe-btn cafe-btn--primary" style="margin-left:auto">
                {{ $category ? '✅ Mettre à jour' : '✅ Créer la catégorie' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Sync color picker ↔ text input
const picker = document.getElementById('color');
const text   = document.getElementById('color_text');
if (picker && text) {
    picker.addEventListener('input', () => { text.value = picker.value; });
    text.addEventListener('input', () => {
        if (/^#[0-9A-Fa-f]{6}$/.test(text.value)) picker.value = text.value;
    });
}
</script>
@endpush
@endsection
