@extends('layouts.recruteur')
@section('title', 'CVthèque')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>CVthèque</h1>
    <p>{{ $cvs->total() }} CV{{ $cvs->total() > 1 ? 's' : '' }} disponible{{ $cvs->total() > 1 ? 's' : '' }} sur la plateforme</p>
  </div>
</div>

{{-- Filtres --}}
<div class="rec-card" style="margin-bottom:18px">
  <div class="rec-card__body" style="padding:16px 22px">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
      <div style="display:flex;flex-direction:column;gap:5px;flex:1;min-width:220px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Recherche</label>
        <div style="display:flex;align-items:center;gap:8px;background:#fff;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre, compétences…" style="border:none;outline:none;font-family:inherit;font-size:13.5px;color:#042C53;background:transparent;width:100%">
        </div>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:180px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Pays</label>
        <select name="pays" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Tous les pays</option>
          @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso'] as $p)
            <option value="{{ $p }}" {{ request('pays') === $p ? 'selected' : '' }}>{{ $p }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="rec-btn rec-btn--primary">Rechercher</button>
      @if(request()->hasAny(['q','pays']))
        <a href="{{ route('recruteur.cvtheque') }}" class="rec-btn rec-btn--outline">Effacer</a>
      @endif
    </form>
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
        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:3px"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        {{ $cv->pays }}{{ $cv->ville ? ', '.$cv->ville : '' }}
      </p>
      @if($cv->competences)
        <p style="font-size:12.5px;color:#475569;margin:0 0 12px;line-height:1.5">{{ Str::limit($cv->competences, 80) }}</p>
      @endif
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;width:100%;gap:8px">
      <span style="font-size:11.5px;color:#94a3b8">{{ $cv->vues }} vue{{ $cv->vues > 1 ? 's' : '' }}</span>
      <div style="display:flex;gap:8px;align-items:center">
        {{-- Bouton favori --}}
        <form method="POST" action="{{ route('recruteur.cvtheque.favoris', $cv) }}" style="margin:0">
          @csrf
          @php $isFavori = in_array($cv->id, $favorisCvIds); @endphp
          <button type="submit"
                  title="{{ $isFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
                  style="padding:5px 8px;background:{{ $isFavori ? '#fef9c3' : '#f1f5f9' }};border:1.5px solid {{ $isFavori ? '#fde68a' : '#e2e8f0' }};border-radius:6px;cursor:pointer;font-size:14px;line-height:1">
            @if($isFavori)
              <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            @else
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            @endif
          </button>
        </form>
        {{-- Contacter --}}
        @if(auth()->user()->premium)
          <a href="mailto:{{ $cv->candidat->email }}" class="rec-btn rec-btn--primary rec-btn--sm">Contacter</a>
        @else
          <a href="{{ route('recruteur.abonnement') }}" class="rec-btn rec-btn--outline rec-btn--sm" title="Fonctionnalité Premium">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Premium
          </a>
        @endif
      </div>
    </div>
  </div>
  @empty
    <div style="grid-column:1/-1">
      <div class="rec-empty">
        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
        <h3>Aucun CV trouvé</h3>
        <p>Essayez d'ajuster vos critères de recherche.</p>
      </div>
    </div>
  @endforelse
</div>

@if($cvs->hasPages())
  <div style="margin-top:24px">{{ $cvs->links() }}</div>
@endif
@endsection
