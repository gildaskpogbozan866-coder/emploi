@extends('layouts.app')
@section('title', 'Candidature envoyée !')

@section('content')
<section class="section" style="padding:80px 0;text-align:center">
  <div class="container" style="max-width:560px">
    <div style="width:80px;height:80px;border-radius:50%;background:#e6f9f0;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
      <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    </div>
    <h1 style="font-size:1.8rem;color:#042C53;margin-bottom:12px">Candidature envoyée !</h1>
    <p style="color:#64748b;margin-bottom:8px">Votre candidature pour <strong>{{ $offre->titre }}</strong> chez <strong>{{ $offre->entreprise }}</strong> a bien été transmise.</p>
    <p style="color:#64748b;margin-bottom:32px">Le recruteur vous contactera si votre profil correspond.</p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="{{ route('offre.list') }}" class="btn btn--blue">Voir d'autres offres</a>
      @auth
      <a href="{{ route('candidat.candidatures') }}" class="btn btn--outline">Mes candidatures</a>
      @endauth
    </div>
  </div>
</section>
@endsection
