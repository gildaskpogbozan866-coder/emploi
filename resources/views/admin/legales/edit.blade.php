@extends('layouts.admin')
@section('title', 'Modifier — ' . $page->titre)

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css">
<style>
  .note-editor.note-frame { border: 1.5px solid #d1d5db; border-radius: 8px; overflow: hidden; }
  .note-editor.note-frame .note-toolbar { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 6px 8px; }
  .note-editor.note-frame .note-editable { min-height: 440px; padding: 20px 24px; font-size: 14px; line-height: 1.75; color: #1e293b; font-family: inherit; }
  .note-editor.note-frame.focused { border-color: #185FA5; box-shadow: 0 0 0 3px rgba(24,95,165,.1); }
  .note-btn { border-radius: 5px !important; font-size: 12px !important; }
  .note-modal-footer .btn { border-radius: 7px !important; }
</style>
@endsection

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.legales.index') }}" class="adm-back-link">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Pages légales
    </a>
    <h1>{{ $page->titre }}</h1>
    <p>Publié sur <code>/legale/{{ $slug }}</code></p>
  </div>
  <a href="{{ route('legale.show', $slug) }}" target="_blank" class="adm-btn adm-btn--outline">
    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
    </svg>
    Voir la page
  </a>
</div>

@if($errors->any())
<div class="alert alert--error" style="margin-bottom:20px">
  @foreach($errors->all() as $e)<p style="margin:2px 0">{{ $e }}</p>@endforeach
</div>
@endif

<form method="POST" action="{{ route('admin.legales.update', $slug) }}" id="legaleForm">
  @csrf
  @method('PUT')

  <div class="adm-form-card">

    <div class="adm-form-field">
      <label class="adm-form-label">Titre de la page</label>
      <input type="text" name="titre" value="{{ old('titre', $page->titre) }}"
             class="adm-form-input" placeholder="Titre de la page légale">
    </div>

    <div class="adm-form-field">
      <label class="adm-form-label">Contenu</label>
      <textarea id="summernote" name="contenu">{{ old('contenu', $page->contenu) }}</textarea>
    </div>

  </div>

  <div class="adm-form-actions">
    <button type="submit" class="adm-btn adm-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      </svg>
      Enregistrer
    </button>
    <a href="{{ route('admin.legales.index') }}" class="adm-btn adm-btn--outline">Annuler</a>
  </div>

</form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-fr-FR.min.js"></script>
<script>
$(function () {
  $('#summernote').summernote({
    lang: 'fr-FR',
    height: 440,
    toolbar: [
      ['style',  ['style']],
      ['font',   ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
      ['para',   ['ul', 'ol', 'paragraph']],
      ['table',  ['table']],
      ['insert', ['link', 'hr']],
      ['view',   ['fullscreen', 'codeview']],
    ],
    styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote'],
    callbacks: {
      onInit: function () {
        $('.note-editable').on('focus', function () {
          $(this).closest('.note-editor').addClass('focused');
        }).on('blur', function () {
          $(this).closest('.note-editor').removeClass('focused');
        });
      }
    }
  });

  $('#legaleForm').on('submit', function () {
    $('#summernote').val($('#summernote').summernote('code'));
  });
});
</script>
@endsection
