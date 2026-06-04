@extends('layouts.app')
@section('title', 'Offre soumise — Emploi Bouge Bénin')

@section('content')
<section style="padding:80px 20px;background:#f8fafc;min-height:70vh;display:flex;align-items:center;justify-content:center">
  <div style="max-width:560px;text-align:center">
    <div style="width:88px;height:88px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
      <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    </div>
    <h1 style="font-size:2rem;font-weight:800;color:#042C53;margin:0 0 14px">Offre soumise avec succès !</h1>
    <p style="font-size:1.05rem;color:#64748b;line-height:1.65;margin:0 0 10px">
      Votre offre <strong>« {{ $offre->titre }} »</strong> a été transmise à notre équipe pour validation.
    </p>
    <p style="font-size:14px;color:#94a3b8;margin:0 0 32px">Elle sera publiée dans les 24h après vérification.</p>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:22px;margin-bottom:28px">
      <div style="display:flex;align-items:flex-start;gap:12px;text-align:left">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2" style="flex-shrink:0;margin-top:2px"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
        <div>
          <p style="font-weight:700;color:#042C53;margin:0 0 4px">{{ $offre->titre }}</p>
          <p style="font-size:13px;color:#64748b;margin:0">{{ $offre->entreprise }} · {{ $offre->localisation }} · {{ $offre->type }}</p>
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      @auth
        <a href="{{ route('recruteur.offres') }}" style="padding:11px 24px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:14px;text-decoration:none">
          Mes offres publiées
        </a>
      @endauth
      <a href="{{ route('offre.list') }}" style="padding:11px 24px;background:#f1f5f9;color:#374151;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none">
        Explorer les offres
      </a>
    </div>
  </div>
</section>
@endsection
