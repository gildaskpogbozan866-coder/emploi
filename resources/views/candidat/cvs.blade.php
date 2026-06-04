@extends('layouts.candidat')
@section('title', 'Mes CVs')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Mes CVs</h1>
    <p class="cand-page-header__sub">Gérez les CVs que vous avez déposés sur la plateforme</p>
  </div>
  <div class="cand-page-header__actions">
    <a href="{{ route('cv.public.depot') }}" class="cand-btn cand-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Déposer un CV
    </a>
  </div>
</div>

@if($cvs->isEmpty())
  <div class="cand-card">
    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <p class="cand-empty__title">Aucun CV déposé</p>
      <p class="cand-empty__text">Déposez votre CV pour être visible auprès des recruteurs et augmenter vos chances d'être contacté.</p>
      <a href="{{ route('cv.public.depot') }}" class="cand-btn cand-btn--primary">Déposer mon premier CV</a>
    </div>
  </div>
@else
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px">
    @foreach($cvs as $cv)
    <div class="cand-card" style="margin-bottom:0">
      <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
        <div style="width:48px;height:48px;border-radius:50%;background:rgba(55,138,221,0.12);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:#185FA5;flex-shrink:0">
          {{ auth()->user()->initiale }}
        </div>
        <div style="flex:1;min-width:0">
          <p style="font-weight:700;color:#042C53;margin:0;font-size:15px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $cv->titre_poste }}</p>
          <p style="font-size:12px;color:#94a3b8;margin:2px 0 0">{{ $cv->pays }}{{ $cv->ville ? ', '.$cv->ville : '' }}</p>
        </div>
      </div>

      @if($cv->competences)
        <p style="font-size:13px;color:#64748b;margin:0 0 14px;line-height:1.5">{{ Str::limit($cv->competences, 90) }}</p>
      @endif

      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto">
        <span class="cand-badge cand-badge--{{ $cv->plan === 'premium' ? 'green' : 'blue' }}">
          {{ $cv->plan === 'premium' ? '★ Premium' : 'Gratuit' }}
        </span>
        <div style="display:flex;gap:8px">
          <a href="{{ route('candidat.cvs.edit', $cv) }}" class="cand-btn cand-btn--outline cand-btn--sm">Modifier</a>
          <form method="POST" action="{{ route('candidat.cvs.destroy', $cv) }}" onsubmit="return confirm('Supprimer ce CV ?')">
            @csrf @method('DELETE')
            <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm">Supprimer</button>
          </form>
        </div>
      </div>
      <p style="font-size:11.5px;color:#94a3b8;margin-top:10px">Déposé le {{ $cv->created_at->format('d/m/Y') }} · {{ $cv->vues }} vue{{ $cv->vues > 1 ? 's' : '' }}</p>
    </div>
    @endforeach
  </div>
@endif
@endsection
