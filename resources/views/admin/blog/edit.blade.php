@extends('layouts.admin')
@section('title', 'Modifier l\'article')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Modifier l'article</h1>
    <p>{{ Str::limit($article->titre, 60) }}</p>
  </div>
  <div class="adm-topbar__actions">
    <a href="{{ route('admin.blog.list') }}" class="adm-btn adm-btn--outline">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Retour
    </a>
  </div>
</div>

<div class="adm-card" style="max-width:900px">
  <div class="adm-card__body">
    <form method="POST" action="{{ route('admin.blog.update', $article) }}" enctype="multipart/form-data" class="adm-form">
      @csrf @method('PUT')

      <div class="adm-field">
        <label class="adm-label">Titre <span style="color:#e53e3e">*</span></label>
        <input class="adm-input" type="text" name="titre" value="{{ old('titre', $article->titre) }}" required>
        @error('titre')<p class="adm-error">{{ $message }}</p>@enderror
      </div>

      <div class="adm-form-grid">
        <div class="adm-field">
          <label class="adm-label">Catégorie</label>
          <select class="adm-select-field" name="categorie">
            <option value="">-- Catégorie --</option>
            @foreach(['Conseils CV','Entretien','Remote','Emploi','Formation','Entrepreneuriat'] as $cat)
              <option value="{{ $cat }}" {{ old('categorie', $article->categorie) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div class="adm-field">
          <label class="adm-label">Temps de lecture (min)</label>
          <input class="adm-input" type="number" name="temps_lecture" value="{{ old('temps_lecture', $article->temps_lecture) }}" min="1" max="60">
        </div>
        <div class="adm-field">
          <label class="adm-label">Statut <span style="color:#e53e3e">*</span></label>
          <select class="adm-select-field" name="statut" required>
            <option value="brouillon" {{ old('statut', $article->statut) === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
            <option value="publie" {{ old('statut', $article->statut) === 'publie' ? 'selected' : '' }}>Publié</option>
            <option value="archive" {{ old('statut', $article->statut) === 'archive' ? 'selected' : '' }}>Archivé</option>
          </select>
        </div>
      </div>

      <div class="adm-field">
        <label class="adm-label">Extrait</label>
        <textarea class="adm-textarea" name="extrait" rows="2">{{ old('extrait', $article->extrait) }}</textarea>
      </div>

      <div class="adm-field">
        <label class="adm-label">Nouvelle image de couverture</label>
        @if($article->image)
          <img src="{{ asset('storage/' . $article->image) }}" style="height:80px;border-radius:8px;margin-bottom:10px;display:block;object-fit:cover">
        @endif
        <input type="file" class="adm-input" name="image" accept="image/*" style="padding:8px 12px">
      </div>

      <div class="adm-field">
        <label class="adm-label">Contenu <span style="color:#e53e3e">*</span></label>
        <textarea class="adm-textarea" name="contenu" rows="20" required>{{ old('contenu', $article->contenu) }}</textarea>
        @error('contenu')<p class="adm-error">{{ $message }}</p>@enderror
      </div>

      <div style="display:flex;gap:10px;padding-top:8px">
        <button type="submit" class="adm-btn adm-btn--primary">Mettre à jour l'article</button>
        <a href="{{ route('admin.blog.list') }}" class="adm-btn adm-btn--outline">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection
