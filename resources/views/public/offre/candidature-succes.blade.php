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

    @if(session('nouveau_cv_id'))
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:16px 20px;margin-bottom:28px;text-align:left;display:flex;gap:12px;align-items:flex-start">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#1e40af" stroke-width="2" style="flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <p style="font-size:13.5px;color:#1e40af;margin:0;line-height:1.6">
        Le CV que vous venez de téléverser a été ajouté à votre espace « Mes CV », mais reste privé — il lui manque encore des informations (métier, ville, compétences...). <a href="{{ route('candidat.cvs.edit', session('nouveau_cv_id')) }}" style="font-weight:700;text-decoration:underline">Complétez-le</a> pour qu'il apparaisse dans la CVthèque.
      </p>
    </div>
    @endif

    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="{{ route('offre.list') }}"
         style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:#185FA5;color:#fff;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;border:2px solid #185FA5;transition:background .18s">
        Voir d'autres offres
      </a>
      @auth
      <a href="{{ route('candidat.candidatures') }}"
         style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:#fff;color:#042C53;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;border:2px solid #042C53;transition:background .18s">
        Mes candidatures
      </a>
      @endauth
    </div>
  </div>
</section>
@endsection
