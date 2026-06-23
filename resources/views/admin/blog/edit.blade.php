@extends('layouts.admin')
@section('title', 'Modifier l\'article')

@section('css')
<style>
/* ── Page wrapper ── */
.blog-editor { max-width: 1200px; }
.blog-editor__grid {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 28px;
  align-items: start;
}
.blog-editor__col  { display: flex; flex-direction: column; gap: 20px; }
.blog-editor__side { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 24px; }

/* ── Cards ── */
.be-card {
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 1px 4px rgba(0,0,0,.06);
  overflow: hidden;
}
.be-card__head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 22px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}
.be-card__head-title {
  font-size: 11px;
  font-weight: 700;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: .08em;
}
.be-card__head-meta { font-size: 12px; color: #94a3b8; }
.be-card__body { padding: 22px; display: flex; flex-direction: column; gap: 18px; }

/* ── Fields ── */
.be-field { display: flex; flex-direction: column; gap: 7px; }
.be-label {
  font-size: 13px; font-weight: 600; color: #1e293b;
  display: flex; align-items: center; justify-content: space-between;
}
.be-req     { color: #e53e3e; margin-left: 2px; }
.be-counter { font-size: 11px; font-weight: 400; color: #94a3b8; }
.be-input, .be-select, .be-textarea {
  width: 100%;
  padding: 11px 14px;
  border: 1.5px solid #d1d5db;
  border-radius: 9px;
  font-size: 14px;
  color: #1e293b;
  font-family: inherit;
  background: #fff;
  transition: border-color .18s, box-shadow .18s;
  box-sizing: border-box;
  appearance: none;
}
.be-input:focus, .be-select:focus, .be-textarea:focus {
  outline: none;
  border-color: #042C53;
  box-shadow: 0 0 0 3px rgba(4,44,83,.1);
}
.be-input::placeholder, .be-textarea::placeholder { color: #b0bec5; }
.be-textarea { resize: vertical; line-height: 1.6; }
.be-select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  padding-right: 36px;
  cursor: pointer;
}
.be-hint  { font-size: 12px; color: #94a3b8; line-height: 1.4; }
.be-error { font-size: 12px; color: #e53e3e; }

/* ── AI bar ── */
.be-ai-bar {
  padding: 14px 22px;
  background: linear-gradient(135deg, #eef4ff 0%, #e8f2ff 100%);
  border-bottom: 1px solid #c7d9f8;
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}
.be-ai-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 9px 20px;
  background: linear-gradient(135deg, #042C53 0%, #1155a6 100%);
  color: #fff;
  border: none;
  border-radius: 9px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  white-space: nowrap;
  transition: opacity .18s, transform .12s, box-shadow .18s;
  box-shadow: 0 2px 8px rgba(4,44,83,.25);
}
.be-ai-btn:hover:not(:disabled) { opacity: .88; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(4,44,83,.3); }
.be-ai-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }
.be-ai-note { font-size: 12px; color: #3b5ba5; line-height: 1.5; flex: 1; min-width: 160px; }
.be-ai-spinner {
  display: none;
  width: 15px; height: 15px;
  border: 2px solid rgba(255,255,255,.35);
  border-top-color: #fff;
  border-radius: 50%;
  animation: be-spin .65s linear infinite;
  flex-shrink: 0;
}
@keyframes be-spin { to { transform: rotate(360deg); } }

/* ── Summernote resets ── */
.note-editor.note-frame {
  border: 1.5px solid #d1d5db !important;
  border-radius: 10px !important;
  overflow: hidden;
}
.note-editor.note-frame .note-toolbar {
  background: #f8fafc !important;
  border-bottom: 1px solid #e2e8f0 !important;
  padding: 6px 8px !important;
}
.note-editor.note-frame .note-editable {
  padding: 16px 18px !important;
  font-size: 14px !important;
  line-height: 1.75 !important;
  color: #1e293b !important;
  font-family: inherit !important;
}
.note-editor.note-frame.focused {
  border-color: #042C53 !important;
  box-shadow: 0 0 0 3px rgba(4,44,83,.1) !important;
}
.note-btn { border-radius: 5px !important; }

/* ── Image ── */
.be-img-current {
  width: 100%; height: 150px;
  border-radius: 9px; overflow: hidden; margin-bottom: 10px;
}
.be-img-current img { width: 100%; height: 100%; object-fit: cover; }
.be-img-zone {
  border: 2px dashed #d1d5db;
  border-radius: 10px;
  padding: 16px;
  text-align: center;
  background: #fafbfc;
  transition: border-color .18s, background .18s;
  cursor: pointer;
  position: relative;
}
.be-img-zone:hover { border-color: #042C53; background: #f0f6ff; }
.be-img-zone input[type=file] {
  position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.be-img-zone__text { font-size: 12px; color: #64748b; }
.be-img-zone__text strong { color: #042C53; }

/* ── Buttons ── */
.be-save-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 13px 20px;
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  color: #1a1200;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(245,158,11,.3);
  transition: opacity .18s, transform .1s;
}
.be-save-btn:hover { opacity: .9; transform: translateY(-1px); }
.be-cancel-link {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px 14px;
  color: #64748b;
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
  border-radius: 8px;
  transition: background .15s, color .15s;
}
.be-cancel-link:hover { background: #f1f5f9; color: #042C53; }

/* ── Status ── */
.be-status-row {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 14px;
  background: #f8fafc;
  border-radius: 9px;
  border: 1px solid #e2e8f0;
  font-size: 13px; color: #64748b;
}
.be-status-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.be-status-dot--draft   { background: #f59e0b; }
.be-status-dot--publish { background: #10b981; }
.be-status-dot--archive { background: #94a3b8; }

/* ── Meta info ── */
.be-meta-row {
  display: flex; align-items: center; justify-content: space-between;
  font-size: 13px; padding: 6px 0;
  border-bottom: 1px solid #f1f5f9;
}
.be-meta-row:last-child { border-bottom: none; }
.be-meta-key   { color: #94a3b8; }
.be-meta-value { color: #1e293b; font-weight: 500; }
.be-meta-code  {
  font-size: 11px; background: #f1f5f9;
  padding: 3px 8px; border-radius: 5px; color: #475569; font-family: monospace;
}

/* ── View link ── */
.be-view-link {
  display: flex; align-items: center; gap: 6px;
  font-size: 13px; color: #042C53; font-weight: 500;
  text-decoration: none;
}
.be-view-link:hover { text-decoration: underline; }

/* ── Toast ── */
.be-toast {
  position: fixed; bottom: 28px; right: 28px;
  padding: 13px 22px; border-radius: 11px;
  font-size: 14px; font-weight: 600; color: #fff;
  z-index: 9999; max-width: 360px;
  opacity: 0; transform: translateY(60px);
  transition: opacity .28s, transform .28s;
  pointer-events: none;
  box-shadow: 0 6px 24px rgba(0,0,0,.18);
}
.be-toast.show { opacity: 1; transform: translateY(0); pointer-events: auto; }
.be-toast--ok  { background: #059669; }
.be-toast--err { background: #dc2626; }

/* ── Responsive ── */
@media (max-width: 900px) {
  .blog-editor__grid { grid-template-columns: 1fr; }
  .blog-editor__side { position: static; }
}
@media (max-width: 600px) {
  .be-card__body { padding: 16px; gap: 14px; }
  .be-card__head { padding: 13px 16px; }
  .be-ai-bar { padding: 12px 16px; }
  .blog-editor__grid { gap: 16px; }
}
</style>
@endsection

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Modifier l'article</h1>
    <p>{{ Str::limit($article->titre, 65) }}</p>
  </div>
  <div class="adm-topbar__actions">
    @if($article->statut === 'publie')
      <a href="{{ route('blog.detail', $article->slug) }}" target="_blank" class="adm-btn adm-btn--outline" style="margin-right:6px">
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        Voir l'article
      </a>
    @endif
    <a href="{{ route('admin.blog.list') }}" class="adm-btn adm-btn--outline">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Retour
    </a>
  </div>
</div>

<form method="POST" action="{{ route('admin.blog.update', $article) }}" enctype="multipart/form-data" class="blog-editor">
  @csrf @method('PUT')
  <div class="blog-editor__grid">

    {{-- ── Colonne principale ── --}}
    <div class="blog-editor__col">

      {{-- Titre --}}
      <div class="be-card">
        <div class="be-card__body" style="gap:0;padding-bottom:22px">
          <div class="be-field">
            <label class="be-label" for="titre">
              Titre de l'article <span class="be-req">*</span>
              <span class="be-counter"><span id="titre-count">0</span>&nbsp;/&nbsp;300</span>
            </label>
            <input class="be-input" type="text" id="titre" name="titre"
                   value="{{ old('titre', $article->titre) }}"
                   placeholder="Titre de l'article…" required
                   oninput="updateCount(this,'titre-count',300)"
                   style="font-size:16px;font-weight:600;padding:13px 16px">
            @error('titre')<p class="be-error">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      {{-- Extrait --}}
      <div class="be-card">
        <div class="be-card__head">
          <span class="be-card__head-title">Extrait / Résumé</span>
        </div>
        <div class="be-card__body">
          <div class="be-field">
            <label class="be-label" for="extrait">
              Description courte
              <span class="be-counter"><span id="extrait-count">0</span>&nbsp;/&nbsp;500</span>
            </label>
            <textarea class="be-textarea" id="extrait" name="extrait" rows="3"
                      placeholder="Résumé visible dans la liste des articles…"
                      oninput="updateCount(this,'extrait-count',500)">{{ old('extrait', $article->extrait) }}</textarea>
            <p class="be-hint">Ce texte s'affiche dans les aperçus et sur les réseaux sociaux.</p>
          </div>
        </div>
      </div>

      {{-- Éditeur contenu --}}
      <div class="be-card">
        <div class="be-card__head">
          <span class="be-card__head-title">Contenu de l'article <span class="be-req">*</span></span>
        </div>

        <div class="be-ai-bar">
          <button type="button" class="be-ai-btn" id="ia-btn" onclick="genererIA()">
            <span class="be-ai-spinner" id="ia-spinner"></span>
            <svg id="ia-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
            </svg>
            Générer avec l'IA
          </button>
          <span class="be-ai-note">Régénère le contenu à partir du titre, de la catégorie et de l'extrait</span>
        </div>

        <div style="padding:0">
          <textarea id="summernote-contenu" name="contenu">{{ old('contenu', $article->contenu) }}</textarea>
          @error('contenu')<p class="be-error" style="padding:8px 22px">{{ $message }}</p>@enderror
        </div>
      </div>

    </div>{{-- /.blog-editor__col --}}

    {{-- ── Sidebar ── --}}
    <div class="blog-editor__side">

      {{-- Publication --}}
      <div class="be-card">
        <div class="be-card__head">
          <span class="be-card__head-title">Publication</span>
          @if($article->publie_le)
            <span class="be-card__head-meta">{{ $article->publie_le->format('d/m/Y') }}</span>
          @endif
        </div>
        <div class="be-card__body">

          <div class="be-field">
            <label class="be-label" for="statut">Statut <span class="be-req">*</span></label>
            <select class="be-select" id="statut" name="statut" required onchange="updateStatusIndicator(this)">
              <option value="brouillon" {{ old('statut', $article->statut) === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
              <option value="publie"    {{ old('statut', $article->statut) === 'publie'    ? 'selected' : '' }}>Publié</option>
              <option value="archive"   {{ old('statut', $article->statut) === 'archive'   ? 'selected' : '' }}>Archivé</option>
            </select>
            <div class="be-status-row">
              <span class="be-status-dot" id="status-dot"></span>
              <span id="status-label"></span>
            </div>
          </div>

          <div class="be-field">
            <label class="be-label" for="categorie">Catégorie</label>
            <select class="be-select" id="categorie" name="categorie">
              <option value="">Choisir une catégorie…</option>
              @foreach(['Conseils CV','Entretien','Remote','Emploi','Formation','Entrepreneuriat'] as $cat)
                <option value="{{ $cat }}" {{ old('categorie', $article->categorie) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
              @endforeach
            </select>
          </div>

          <div class="be-field">
            <label class="be-label" for="temps_lecture">Temps de lecture</label>
            <div style="display:flex;align-items:center;gap:8px">
              <input class="be-input" type="number" id="temps_lecture" name="temps_lecture"
                     value="{{ old('temps_lecture', $article->temps_lecture) }}" min="1" max="60"
                     style="width:80px;flex-shrink:0">
              <span class="be-hint" style="margin:0">minutes</span>
            </div>
          </div>

          <div style="display:flex;flex-direction:column;gap:8px;padding-top:4px">
            <button type="submit" class="be-save-btn">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Mettre à jour
            </button>
            <a href="{{ route('admin.blog.list') }}" class="be-cancel-link">Annuler</a>
          </div>

        </div>
      </div>

      {{-- Image --}}
      <div class="be-card">
        <div class="be-card__head">
          <span class="be-card__head-title">Image de couverture</span>
        </div>
        <div class="be-card__body">
          @if($article->image)
            <div class="be-img-current" id="img-preview-wrap">
              <img id="img-preview" src="{{ asset('storage/'.$article->image) }}" alt="">
            </div>
          @else
            <div class="be-img-current" id="img-preview-wrap" style="display:none">
              <img id="img-preview" src="" alt="">
            </div>
          @endif
          <div class="be-img-zone" onclick="document.getElementById('img-input').click()">
            <input type="file" id="img-input" name="image" accept="image/*" onchange="previewImage(this)" style="display:none">
            <p class="be-img-zone__text">
              <strong>Changer l'image</strong> · JPG, PNG · max 3 Mo
            </p>
          </div>
          <p class="be-hint" style="margin-top:6px">Laisser vide pour conserver l'image actuelle.</p>
        </div>
      </div>

      {{-- Informations --}}
      <div class="be-card">
        <div class="be-card__head">
          <span class="be-card__head-title">Informations</span>
        </div>
        <div class="be-card__body" style="gap:0;padding:16px 20px">
          <div class="be-meta-row">
            <span class="be-meta-key">Auteur</span>
            <span class="be-meta-value">{{ $article->auteur?->name ?? '—' }}</span>
          </div>
          <div class="be-meta-row">
            <span class="be-meta-key">Créé le</span>
            <span class="be-meta-value">{{ $article->created_at->format('d/m/Y') }}</span>
          </div>
          <div class="be-meta-row">
            <span class="be-meta-key">Modifié</span>
            <span class="be-meta-value">{{ $article->updated_at->diffForHumans() }}</span>
          </div>
          <div class="be-meta-row">
            <span class="be-meta-key">Slug</span>
            <span class="be-meta-code">{{ $article->slug }}</span>
          </div>
        </div>
      </div>

    </div>{{-- /.blog-editor__side --}}
  </div>
</form>

<div class="be-toast" id="be-toast"></div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
$(function() {
  $('#summernote-contenu').summernote({
    lang: 'fr-FR',
    height: 460,
    placeholder: 'Rédigez votre article ici, ou cliquez sur "Générer avec l\'IA" pour régénérer le contenu…',
    toolbar: [
      ['style',    ['style']],
      ['font',     ['bold','italic','underline','strikethrough','clear']],
      ['fontsize', ['fontsize']],
      ['color',    ['forecolor','backcolor']],
      ['para',     ['ul','ol','paragraph']],
      ['table',    ['table']],
      ['insert',   ['link','picture','hr']],
      ['view',     ['codeview','fullscreen']],
    ],
    fontSizes: ['12','13','14','15','16','18','20','24','28','32','36'],
    styleTags: ['p','h2','h3','h4','blockquote'],
    callbacks: {
      onImageUpload: function(files) {
        $.each(files, function(i, file) {
          var reader = new FileReader();
          reader.onload = function(e) {
            var img = $('<img>').attr('src', e.target.result).css('max-width','100%');
            $('#summernote-contenu').summernote('insertNode', img[0]);
          };
          reader.readAsDataURL(file);
        });
      }
    }
  });

  updateCount(document.getElementById('titre'),   'titre-count',   300);
  updateCount(document.getElementById('extrait'), 'extrait-count', 500);
  updateStatusIndicator(document.getElementById('statut'));
});

function updateCount(el, id, max) {
  var n = el.value.length;
  var s = document.getElementById(id);
  if (!s) return;
  s.textContent = n;
  s.parentElement.style.color = n > max * .88 ? '#e53e3e' : '#94a3b8';
}

function updateStatusIndicator(sel) {
  var dot   = document.getElementById('status-dot');
  var label = document.getElementById('status-label');
  if (!dot || !label) return;
  var map = {
    publie:    { cls: 'be-status-dot--publish', txt: 'Publié — visible par tous' },
    brouillon: { cls: 'be-status-dot--draft',   txt: 'Brouillon — non visible' },
    archive:   { cls: 'be-status-dot--archive', txt: 'Archivé — masqué' },
  };
  var info = map[sel.value] || map.brouillon;
  dot.className   = 'be-status-dot ' + info.cls;
  label.textContent = info.txt;
}

function previewImage(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    document.getElementById('img-preview').src = e.target.result;
    document.getElementById('img-preview-wrap').style.display = 'block';
  };
  reader.readAsDataURL(input.files[0]);
}

function genererIA() {
  var titre = document.getElementById('titre').value.trim();
  if (!titre) {
    showToast('Veuillez d\'abord renseigner le titre de l\'article.', 'err');
    document.getElementById('titre').focus();
    return;
  }
  var categorie = document.getElementById('categorie').value;
  var extrait   = document.getElementById('extrait').value.trim();

  var btn     = document.getElementById('ia-btn');
  var spinner = document.getElementById('ia-spinner');
  var icon    = document.getElementById('ia-icon');
  btn.disabled          = true;
  spinner.style.display = 'inline-block';
  icon.style.display    = 'none';

  $.ajax({
    url:    '{{ route('admin.blog.generer-ia') }}',
    method: 'POST',
    data:   { _token: '{{ csrf_token() }}', titre, categorie, extrait },
    success: function(res) {
      if (res.contenu) {
        $('#summernote-contenu').summernote('code', res.contenu);
        showToast('Contenu régénéré avec succès !', 'ok');
      } else {
        showToast('Réponse inattendue de l\'IA.', 'err');
      }
    },
    error: function(xhr) {
      var msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Erreur lors de la génération.';
      showToast(msg, 'err');
    },
    complete: function() {
      btn.disabled          = false;
      spinner.style.display = 'none';
      icon.style.display    = 'inline';
    }
  });
}

function showToast(msg, type) {
  var t = document.getElementById('be-toast');
  t.textContent = msg;
  t.className   = 'be-toast be-toast--' + type;
  t.classList.add('show');
  setTimeout(function() { t.classList.remove('show'); }, 4500);
}
</script>
@endsection
