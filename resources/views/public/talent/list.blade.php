@extends('layouts.app')
@section('title', 'Profilthèque — Emploi Bouge Bénin')

@section('content')
<section class="page-hero">
  <div class="container">
    <h1 class="page-hero__title">Profilthèque de talents</h1>
    <p class="page-hero__sub">Des profils compétents, vérifiés, disponibles pour votre équipe.</p>
  </div>
</section>

<section class="section">
  <div class="container">

    {{-- Filtres --}}
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Métier, compétences…"
             style="padding:9px 14px;border:1px solid #e2e8f0;border-radius:8px;font-family:inherit;min-width:220px">
      <select name="pays" style="padding:9px 12px;border:1px solid #e2e8f0;border-radius:8px;font-family:inherit">
        <option value="">Tous les pays</option>
        @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali'] as $pays)
          <option value="{{ $pays }}" {{ request('pays') === $pays ? 'selected' : '' }}>{{ $pays }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn--blue">Rechercher</button>
      @if(request()->hasAny(['q','pays']))
        <a href="{{ route('talent.public.list') }}" class="btn btn--outline">Effacer</a>
      @endif
    </form>

    {{-- Grille --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:18px">
      @forelse($profils as $profil)
      <a href="{{ route('talent.public.detail', $profil) }}" style="text-decoration:none">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:22px;transition:box-shadow .2s;height:100%"
             onmouseover="this.style.boxShadow='0 4px 20px rgba(55,138,221,.14)'"
             onmouseout="this.style.boxShadow='none'">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
            @if($profil->photo)
              <img src="{{ asset('storage/'.$profil->photo) }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover">
            @else
              <div style="width:52px;height:52px;border-radius:50%;background:#EBF4FD;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.2rem;color:#185FA5;flex-shrink:0">
                {{ strtoupper(substr($profil->user->prenom ?? '?', 0, 1)) }}
              </div>
            @endif
            <div>
              <p style="font-weight:700;color:#042C53;margin:0">{{ $profil->user->prenom ?? '' }} {{ substr($profil->user->nom ?? '', 0, 1) }}.</p>
              <p style="font-size:.8rem;color:#185FA5;font-weight:600;margin:0">{{ $profil->metier }}</p>
            </div>
          </div>

          @if($profil->specialite)
            <p style="font-size:.8rem;color:#64748b;margin:0 0 10px">{{ $profil->specialite }}</p>
          @endif

          @if($profil->competences)
            <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px">
              @foreach(array_slice(explode(',', $profil->competences), 0, 4) as $comp)
                <span class="tag" style="font-size:.72rem">{{ trim($comp) }}</span>
              @endforeach
            </div>
          @endif

          <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px">
            <span style="font-size:.78rem;color:#94a3b8">{{ $profil->pays }}{{ $profil->ville ? ', '.$profil->ville : '' }}</span>
            <span class="badge-statut {{ $profil->plan === 'premium' ? 'badge-statut--active' : 'badge-statut--envoyee' }}" style="font-size:.72rem">
              {{ $profil->plan === 'premium' ? '★ Premium' : 'Gratuit' }}
            </span>
          </div>
        </div>
      </a>
      @empty
        <div class="empty-state" style="grid-column:1/-1;text-align:center;padding:48px;color:#64748b">
          Aucun profil talent ne correspond à votre recherche.
        </div>
      @endforelse
    </div>

    <div style="margin-top:32px">{{ $profils->links() }}</div>

  </div>
</section>
@endsection
