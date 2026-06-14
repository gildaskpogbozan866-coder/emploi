@extends('layouts.admin')
@section('title', 'Examiner l\'annonce — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.publicites.index') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">← Retour</a>
    <h1>Annonce : {{ $publicite->titre }}</h1>
    <p>Soumise le {{ $publicite->created_at->format('d/m/Y à H:i') }} par {{ $publicite->user->nom_complet }}</p>
  </div>
  <span class="adm-badge adm-badge--{{ $publicite->statut_badge }}" style="font-size:14px;padding:8px 16px">
    {{ $publicite->statut_label }}
  </span>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:960px">

  {{-- Aperçu image --}}
  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Aperçu de l'annonce</h3>
    </div>
    <div style="padding:20px 24px">
      {{-- Simulation du widget tel qu'il apparaît sur la homepage --}}
      <div style="background:#1e293b;border-radius:12px;padding:8px;max-width:300px;margin:0 auto">
        <div style="position:relative;background:#000;border-radius:8px;overflow:hidden;aspect-ratio:4/3">
          <img src="{{ asset('storage/' . $publicite->image) }}"
               alt="{{ $publicite->titre }}"
               style="width:100%;height:100%;object-fit:contain;display:block">
        </div>
        <div style="padding:8px 4px 2px;display:flex;justify-content:space-between;align-items:center">
          <span style="color:#94a3b8;font-size:11px">{{ $publicite->titre }}</span>
          <span style="color:#64748b;font-size:10px">Aperçu widget</span>
        </div>
      </div>
      <p style="font-size:12px;color:#94a3b8;text-align:center;margin:12px 0 0">
        Rendu approximatif dans le widget de la homepage
      </p>
    </div>
  </div>

  {{-- Détails --}}
  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Détails</h3>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px">
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Annonceur</p>
        <p style="font-weight:600;color:#042C53;margin:0">{{ $publicite->user->nom_complet }}</p>
        <p style="font-size:13px;color:#64748b;margin:2px 0 0">{{ $publicite->user->email }}</p>
      </div>
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Lien cliquable</p>
        @if($publicite->lien)
          <a href="{{ $publicite->lien }}" target="_blank" rel="noopener noreferrer"
             style="color:#185FA5;font-size:13.5px;word-break:break-all">{{ $publicite->lien }}</a>
        @else
          <p style="color:#94a3b8;font-style:italic;margin:0;font-size:13px">Aucun lien</p>
        @endif
      </div>
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Période de diffusion</p>
        <p style="font-weight:600;color:#042C53;margin:0">
          @if($publicite->date_debut || $publicite->date_fin)
            {{ $publicite->date_debut?->format('d/m/Y') ?? 'Dès maintenant' }}
            → {{ $publicite->date_fin?->format('d/m/Y') ?? 'Sans limite' }}
          @else
            Sans limite de date
          @endif
        </p>
      </div>
      @if($publicite->note_annonceur)
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Note de l'annonceur</p>
        <p style="font-size:13.5px;color:#374151;line-height:1.6;margin:0">{{ $publicite->note_annonceur }}</p>
      </div>
      @endif
      @if($publicite->note_admin)
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Note admin précédente</p>
        <p style="font-size:13.5px;color:#dc2626;line-height:1.6;margin:0">{{ $publicite->note_admin }}</p>
      </div>
      @endif
    </div>
  </div>
</div>

{{-- Actions --}}
@if($publicite->statut === 'en_attente')
<div class="adm-card" style="max-width:960px;margin-top:20px">
  <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Décision</h3>
  </div>
  <div style="padding:20px 24px;display:flex;gap:24px;flex-wrap:wrap;align-items:flex-start">

    {{-- Approuver --}}
    <form method="POST" action="{{ route('admin.publicites.approuver', $publicite) }}">
      @csrf
      <button type="submit" class="adm-btn adm-btn--green">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
        Approuver l'annonce
      </button>
    </form>

    {{-- Rejeter --}}
    <form method="POST" action="{{ route('admin.publicites.rejeter', $publicite) }}"
          style="display:flex;flex-direction:column;gap:10px;flex:1;min-width:280px">
      @csrf
      <div>
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Motif du rejet</label>
        <textarea name="note_admin" rows="2" required
                  placeholder="Expliquez pourquoi cette annonce est rejetée…"
                  style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box"></textarea>
        @error('note_admin')<p class="adm-error">{{ $message }}</p>@enderror
      </div>
      <button type="submit" class="adm-btn adm-btn--danger">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        Rejeter l'annonce
      </button>
    </form>
  </div>
</div>
@else
<div class="adm-card" style="max-width:960px;margin-top:20px;padding:20px 24px">
  <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
    @if($publicite->statut === 'approuve')
      <form method="POST" action="{{ route('admin.publicites.rejeter', $publicite) }}"
            style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
        @csrf
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Motif</label>
          <input type="text" name="note_admin" required placeholder="Motif du retrait…"
                 style="padding:9px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;min-width:220px">
        </div>
        <button type="submit" class="adm-btn adm-btn--danger">Retirer l'annonce</button>
      </form>
    @endif
    @if($publicite->statut === 'rejete')
      <form method="POST" action="{{ route('admin.publicites.approuver', $publicite) }}">
        @csrf
        <button type="submit" class="adm-btn adm-btn--green">Approuver finalement</button>
      </form>
    @endif
    <form method="POST" action="{{ route('admin.publicites.destroy', $publicite) }}"
          onsubmit="return confirm('Supprimer définitivement cette annonce ?')">
      @csrf @method('DELETE')
      <button type="submit" class="adm-btn adm-btn--outline" style="color:#dc2626;border-color:#dc2626">Supprimer</button>
    </form>
  </div>
</div>
@endif
@endsection
