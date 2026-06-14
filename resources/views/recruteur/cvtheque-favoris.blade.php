@extends('layouts.recruteur')
@section('title', 'CVs favoris — CVthèque')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>CVs favoris</h1>
    <p>{{ $cvs->total() }} profil{{ $cvs->total() > 1 ? 's' : '' }} enregistré{{ $cvs->total() > 1 ? 's' : '' }}</p>
  </div>
  <div class="rec-topbar__right" style="display:flex;align-items:center;gap:10px">
    <div style="display:flex;align-items:center;gap:8px;background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:10px;padding:8px 16px">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <span style="font-size:13px;color:#0284c7;font-weight:700">{{ $credits }} crédit{{ $credits > 1 ? 's' : '' }}</span>
    </div>
    <a href="{{ route('recruteur.cvtheque') }}" class="rec-btn rec-btn--outline rec-btn--sm">← Toute la CVthèque</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">
  @forelse($cvs as $cv)
  <div class="rec-offer-card" style="flex-direction:column;align-items:flex-start;gap:14px">
    <div style="display:flex;gap:12px;align-items:center;width:100%">
      <div style="width:46px;height:46px;border-radius:50%;background:rgba(55,138,221,0.12);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:#185FA5;flex-shrink:0">
        {{ strtoupper(substr($cv->candidat->prenom ?? '?', 0, 1)) }}
      </div>
      <div style="flex:1;min-width:0">
        <p style="font-weight:700;color:#042C53;margin:0;font-size:14px">
          {{ $cv->candidat->prenom ?? '' }} {{ substr($cv->candidat->nom ?? '', 0, 1) }}.
        </p>
        <p style="font-size:12.5px;color:#185FA5;margin:2px 0 0;font-weight:600">{{ $cv->titre_poste }}</p>
      </div>
      @if($cv->plan === 'premium')
        <span class="rec-badge rec-badge--yellow">Premium</span>
      @endif
    </div>

    <div style="width:100%">
      <p style="font-size:12.5px;color:#94a3b8;margin:0 0 6px">
        {{ $cv->pays }}{{ $cv->ville ? ', '.$cv->ville : '' }}
      </p>
      @if($cv->competences)
        <p style="font-size:12.5px;color:#475569;margin:0;line-height:1.5">{{ Str::limit($cv->competences, 80) }}</p>
      @endif
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;width:100%;border-top:1px solid #f0f2f5;padding-top:12px">
      <form method="POST" action="{{ route('recruteur.cvtheque.favoris', $cv) }}" style="margin:0">
        @csrf
        <button type="submit" style="padding:5px 8px;background:#fef9c3;border:1.5px solid #fde68a;border-radius:6px;cursor:pointer;font-size:14px;line-height:1" title="Retirer des favoris">
          <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </button>
      </form>
      <a href="{{ route('recruteur.cvtheque.show', $cv) }}" class="rec-btn rec-btn--outline rec-btn--sm">Voir profil</a>
    </div>
  </div>
  @empty
    <div style="grid-column:1/-1">
      <div class="rec-empty">
        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        <h3>Aucun CV en favoris</h3>
        <p>Ajoutez des CVs à vos favoris depuis la <a href="{{ route('recruteur.cvtheque') }}">CVthèque</a>.</p>
      </div>
    </div>
  @endforelse
</div>

@if($cvs->hasPages())
  <div style="margin-top:24px">{{ $cvs->links() }}</div>
@endif
@endsection
