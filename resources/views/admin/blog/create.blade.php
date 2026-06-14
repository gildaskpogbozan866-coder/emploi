@extends('layouts.admin')
@section('title', 'Créer un article')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Créer un article</h1>
    <p>Rédigez et publiez un nouvel article sur le blog</p>
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
    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="adm-form">
      @csrf

      <div class="adm-field">
        <label class="adm-label">Titre de l'article <span style="color:#e53e3e">*</span></label>
        <input class="adm-input" type="text" name="titre" value="{{ old('titre') }}" placeholder="Titre accrocheur…" required>
        @error('titre')<p class="adm-error">{{ $message }}</p>@enderror
      </div>

      <div class="adm-form-grid">
        <div class="adm-field">
          <label class="adm-label">Catégorie</label>
          <select class="adm-select-field" name="categorie">
            <option value="">-- Catégorie --</option>
            @foreach(['Conseils CV','Entretien','Remote','Emploi','Formation','Entrepreneuriat'] as $cat)
              <option value="{{ $cat }}" {{ old('categorie') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div class="adm-field">
          <label class="adm-label">Temps de lecture (min)</label>
          <input class="adm-input" type="number" name="temps_lecture" value="{{ old('temps_lecture', 5) }}" min="1" max="60">
        </div>
        <div class="adm-field">
          <label class="adm-label">Statut <span style="color:#e53e3e">*</span></label>
          <select class="adm-select-field" name="statut" required>
            <option value="brouillon" {{ old('statut') !== 'publie' ? 'selected' : '' }}>Brouillon</option>
            <option value="publie" {{ old('statut') === 'publie' ? 'selected' : '' }}>Publier maintenant</option>
          </select>
        </div>
      </div>

      <div class="adm-field">
        <label class="adm-label">Extrait (résumé court)</label>
        <textarea class="adm-textarea" name="extrait" rows="2" placeholder="Résumé affiché dans la liste des articles…">{{ old('extrait') }}</textarea>
      </div>

      <div class="adm-field">
        <label class="adm-label">Image de couverture</label>
        <div id="img-preview-wrap" style="display:none;width:100%;height:200px;border-radius:10px;overflow:hidden;background:#f1f5f9;margin-bottom:10px">
          <img id="img-preview" alt="" style="width:100%;height:100%;object-fit:cover">
        </div>
        <input type="file" class="adm-input" name="image" accept="image/*"
               style="padding:8px 12px" onchange="previewImage(this)">
      </div>

      <div class="adm-field">
        <label class="adm-label">Contenu complet <span style="color:#e53e3e">*</span></label>
        <textarea id="summernote-contenu" name="contenu" required>{{ old('contenu') }}</textarea>
        @error('contenu')<p class="adm-error">{{ $message }}</p>@enderror
      </div>

      <div style="display:flex;gap:10px;padding-top:8px">
        <button type="submit" class="adm-btn adm-btn--yellow">Enregistrer l'article</button>
        <a href="{{ route('admin.blog.list') }}" class="adm-btn adm-btn--outline">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
$('#summernote-contenu').summernote({
  lang: 'fr-FR',
  height: 420,
  placeholder: 'Rédigez votre article ici…',
  toolbar: [
    ['style',   ['style']],
    ['font',    ['bold','italic','underline','strikethrough','clear']],
    ['color',   ['color']],
    ['para',    ['ul','ol','paragraph']],
    ['table',   ['table']],
    ['insert',  ['link','picture']],
    ['view',    ['codeview','fullscreen']],
  ],
  callbacks: {
    onImageUpload: function(files) {
      for (var i = 0; i < files.length; i++) {
        var reader = new FileReader();
        reader.onload = (function() {
          return function(e) {
            var img = $('<img>').attr('src', e.target.result).css('max-width','100%');
            $('#summernote-contenu').summernote('insertNode', img[0]);
          };
        })();
        reader.readAsDataURL(files[i]);
      }
    }
  }
});

function previewImage(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('img-preview').src = e.target.result;
      document.getElementById('img-preview-wrap').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endsection
