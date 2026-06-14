@extends('layouts.admin')
@section('title', isset($faq) ? 'Modifier la question' : 'Nouvelle question')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css">
<style>
  .note-editor.note-frame { border: 1.5px solid #d1d5db; border-radius: 8px; overflow: hidden; }
  .note-editor.note-frame .note-toolbar { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 6px 8px; }
  .note-editor.note-frame .note-editable { min-height: 260px; padding: 16px 20px; font-size: 14px; line-height: 1.75; color: #1e293b; font-family: inherit; }
  .note-editor.note-frame.focused { border-color: #185FA5; box-shadow: 0 0 0 3px rgba(24,95,165,.1); }
  .note-btn { border-radius: 5px !important; font-size: 12px !important; }
</style>
@endsection

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.faqs.index') }}" class="adm-back-link">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      FAQ
    </a>
    <h1>{{ isset($faq) ? 'Modifier la question' : 'Nouvelle question' }}</h1>
  </div>
</div>

@if($errors->any())
<div class="alert alert--error" style="margin-bottom:20px">
  @foreach($errors->all() as $e)<p style="margin:2px 0">{{ $e }}</p>@endforeach
</div>
@endif

<form method="POST" action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" id="faqForm">
  @csrf
  @if(isset($faq)) @method('PUT') @endif

  <div class="adm-form-card">

    {{-- Catégorie --}}
    <div class="adm-form-field">
      <label class="adm-form-label">
        Catégorie
        <small>— ex : Candidats, Recruteurs, Général</small>
      </label>
      <input type="text" name="categorie"
             value="{{ old('categorie', $faq->categorie ?? '') }}"
             list="cats-list"
             placeholder="Choisir ou saisir une catégorie"
             class="adm-form-input">
      <datalist id="cats-list">
        @foreach($categories as $cat)
          <option value="{{ $cat }}">
        @endforeach
      </datalist>
    </div>

    {{-- Question --}}
    <div class="adm-form-field">
      <label class="adm-form-label">Question</label>
      <input type="text" name="question"
             value="{{ old('question', $faq->question ?? '') }}"
             placeholder="Ex : Est-ce gratuit pour les candidats ?"
             class="adm-form-input">
    </div>

    {{-- Réponse — Summernote --}}
    <div class="adm-form-field">
      <label class="adm-form-label">Réponse</label>
      <textarea id="summernote-faq" name="reponse">{{ old('reponse', $faq->reponse ?? '') }}</textarea>
    </div>

    {{-- Ordre + Actif --}}
    <div style="display:flex;gap:24px;align-items:flex-end;flex-wrap:wrap">
      <div style="flex:0 0 160px">
        <label class="adm-form-label">Ordre d'affichage</label>
        <input type="number" name="ordre" min="0"
               value="{{ old('ordre', $faq->ordre ?? 0) }}"
               class="adm-form-input">
      </div>
      <div>
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;user-select:none;padding-bottom:2px">
          <input type="hidden" name="actif" value="0">
          <input type="checkbox" name="actif" value="1"
                 {{ old('actif', $faq->actif ?? true) ? 'checked' : '' }}
                 style="width:16px;height:16px;cursor:pointer;accent-color:#185FA5">
          <span style="font-size:14px;font-weight:600;color:#374151">Visible sur le site</span>
        </label>
      </div>
    </div>

  </div>

  <div class="adm-form-actions">
    <button type="submit" class="adm-btn adm-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      </svg>
      {{ isset($faq) ? 'Enregistrer les modifications' : 'Ajouter la question' }}
    </button>
    <a href="{{ route('admin.faqs.index') }}" class="adm-btn adm-btn--outline">Annuler</a>
  </div>

</form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-fr-FR.min.js"></script>
<script>
$(function () {
  $('#summernote-faq').summernote({
    lang: 'fr-FR',
    height: 260,
    toolbar: [
      ['font',   ['bold', 'italic', 'underline', 'clear']],
      ['para',   ['ul', 'ol']],
      ['insert', ['link', 'hr']],
      ['view',   ['codeview']],
    ],
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

  $('#faqForm').on('submit', function () {
    $('#summernote-faq').val($('#summernote-faq').summernote('code'));
  });
});
</script>
@endsection
