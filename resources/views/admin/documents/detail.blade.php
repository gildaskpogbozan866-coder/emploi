@extends('layouts.admin')
@section('title', 'Document — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.documents.list') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Retour
    </a>
    <h1>{{ $document->nom }}</h1>
    <p>{{ $document->type->nom ?? '' }} · Déposé le {{ $document->created_at->format('d/m/Y') }}</p>
  </div>
  <div>
    <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" data-confirm="Supprimer définitivement ce document ?" data-confirm-btn="Supprimer">
      @csrf @method('DELETE')
      <button type="submit" class="adm-btn adm-btn--danger">Supprimer</button>
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Candidat</h3>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Nom</p><p style="font-weight:600;color:#042C53;margin:0">{{ $document->user->nom_complet }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Email</p><p style="font-weight:600;color:#042C53;margin:0">{{ $document->user->email }}</p></div>
    </div>
  </div>

  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Informations</h3>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Nom</p><p style="font-weight:600;color:#042C53;margin:0">{{ $document->nom }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Type</p><span class="adm-badge adm-badge--blue">{{ $document->type->nom ?? '—' }}</span></div>
      @if($document->pays)
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Localisation</p><p style="font-weight:600;color:#042C53;margin:0">{{ $document->ville ? $document->ville.', ' : '' }}{{ $document->pays }}</p></div>
      @endif
    </div>
  </div>
</div>

@foreach([['Compétences', $document->competences], ['Expérience', $document->experience], ['Formation', $document->formation], ['Langues', $document->langues]] as [$label, $value])
  @if($value)
  <div class="adm-card" style="margin-top:20px">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">{{ $label }}</h3>
    </div>
    <div style="padding:20px 24px"><p style="color:#374151;font-size:14px;line-height:1.65;margin:0">{{ $value }}</p></div>
  </div>
  @endif
@endforeach

<div class="adm-card" style="margin-top:20px;padding:20px 24px">
  @if($document->estImage())
    <img src="{{ asset('storage/'.$document->fichier) }}" alt="{{ $document->nom }}" style="max-width:100%;border-radius:6px">
  @else
    <a href="{{ asset('storage/'.$document->fichier) }}" target="_blank" class="adm-btn adm-btn--primary">
      Télécharger le fichier
    </a>
  @endif
</div>
@endsection
