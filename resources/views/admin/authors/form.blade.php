@extends('cafe-blog::layouts.app')
@section('title', $author ? 'Modifier l\'auteur' : 'Nouvel auteur')

@section('content')
<div style="max-width:700px;margin:2.5rem auto;padding-inline:var(--gutter)">

    <a href="{{ route('cafe-blog.admin.authors.index') }}" style="color:var(--color-roast);font-family:var(--font-ui);font-size:.88rem;text-decoration:none;display:block;margin-bottom:1.5rem">
        ← Retour aux auteurs
    </a>

    <h1 style="font-family:var(--font-display);font-size:1.75rem;color:var(--color-espresso);margin-bottom:2rem">
        {{ $author ? '✏️ Modifier l\'auteur' : '👤 Nouvel auteur' }}
    </h1>

    <form action="{{ $author ? route('cafe-blog.admin.authors.update', $author) : route('cafe-blog.admin.authors.store') }}"
          method="POST" enctype="multipart/form-data"
          style="display:flex;flex-direction:column;gap:1.25rem">
        @csrf
        @if($author) @method('PUT') @endif

        <div class="cafe-form-row">
            <div class="cafe-form-group">
                <label for="name">Nom complet *</label>
                <input type="text" id="name" name="name" class="cafe-input @error('name') cafe-input--error @enderror"
                       value="{{ old('name', $author?->name) }}" required>
                @error('name')<span class="cafe-field-error">{{ $message }}</span>@enderror
            </div>
            <div class="cafe-form-group">
                <label for="email">E-mail * <small style="color:var(--color-text-muted)">(non public)</small></label>
                <input type="email" id="email" name="email" class="cafe-input @error('email') cafe-input--error @enderror"
                       value="{{ old('email', $author?->email) }}" required>
                @error('email')<span class="cafe-field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="cafe-form-group">
            <label for="bio">Biographie</label>
            <textarea id="bio" name="bio" rows="4" class="cafe-textarea" placeholder="Qui est cet auteur ? Son parcours, sa passion pour le café…">{{ old('bio', $author?->bio) }}</textarea>
        </div>

        <div class="cafe-form-row">
            <div class="cafe-form-group">
                <label for="avatar">Photo de profil</label>
                @if($author?->avatar)
                <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}"
                     style="width:64px;height:64px;border-radius:50%;object-fit:cover;margin-bottom:.5rem;border:3px solid var(--color-caramel)">
                @endif
                <input type="file" id="avatar" name="avatar" accept="image/*" class="cafe-input">
                @error('avatar')<span class="cafe-field-error">{{ $message }}</span>@enderror
            </div>
            <div class="cafe-form-group">
                <label for="website">Site web</label>
                <input type="url" id="website" name="website" class="cafe-input"
                       value="{{ old('website', $author?->website) }}" placeholder="https://…">
            </div>
        </div>

        <div class="cafe-form-row">
            <div class="cafe-form-group">
                <label for="twitter">Twitter / X</label>
                <input type="text" id="twitter" name="twitter" class="cafe-input"
                       value="{{ old('twitter', $author?->twitter) }}" placeholder="@utilisateur">
            </div>
            <div class="cafe-form-group">
                <label for="instagram">Instagram</label>
                <input type="text" id="instagram" name="instagram" class="cafe-input"
                       value="{{ old('instagram', $author?->instagram) }}" placeholder="@utilisateur">
            </div>
        </div>

        <div class="cafe-form-row">
            <div class="cafe-form-group">
                <label for="coffee_preference">Préférence café ☕</label>
                <input type="text" id="coffee_preference" name="coffee_preference" class="cafe-input"
                       value="{{ old('coffee_preference', $author?->coffee_preference) }}"
                       placeholder="Éthiopien naturel, torréfaction légère…">
            </div>
            <div class="cafe-form-group">
                <label for="origin_specialty">Origine de spécialité 🌍</label>
                <select id="origin_specialty" name="origin_specialty" class="cafe-input">
                    <option value="">— Aucune spécialité —</option>
                    @foreach(\CafeHistoire\CafeBlog\Models\Post::ORIGINS as $key => $label)
                    <option value="{{ $key }}" {{ old('origin_specialty', $author?->origin_specialty) === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $author?->is_active ?? true) ? 'checked' : '' }}
                   style="width:18px;height:18px;accent-color:var(--color-roast)">
            Auteur actif
        </label>

        <div style="display:flex;gap:1rem;margin-top:.5rem">
            <a href="{{ route('cafe-blog.admin.authors.index') }}" class="cafe-btn cafe-btn--ghost">Annuler</a>
            <button type="submit" class="cafe-btn cafe-btn--primary" style="margin-left:auto">
                {{ $author ? '✅ Mettre à jour' : '✅ Créer l\'auteur' }}
            </button>
        </div>
    </form>
</div>
@endsection
