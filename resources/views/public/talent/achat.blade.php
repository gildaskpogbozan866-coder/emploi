@extends('layouts.app')
@section('title', 'Contacter ce Talent — Emploi Bouge Bénin')

@section('content')
<section style="padding:48px 20px;background:#f8fafc;min-height:60vh">
  <div style="max-width:680px;margin:0 auto">
    <div style="margin-bottom:20px">
      <a href="{{ route('talent.public.detail', $profil) }}" style="color:#185FA5;text-decoration:none;font-size:13.5px">
        ← Retour au profil
      </a>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:32px;margin-bottom:24px">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
        <div style="width:56px;height:56px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.2rem;color:#185FA5;flex-shrink:0">
          {{ strtoupper(substr($profil->user->prenom ?? '?', 0, 1)) }}
        </div>
        <div>
          <h2 style="font-size:1.2rem;font-weight:800;color:#042C53;margin:0 0 4px">{{ $profil->user->prenom }}</h2>
          <p style="font-size:14px;color:#185FA5;font-weight:600;margin:0">{{ $profil->metier }}</p>
        </div>
      </div>

      <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:20px;margin-bottom:24px">
        <p style="font-size:14px;color:#15803d;margin:0">
          <strong>Abonnement Premium requis (3 000 FCFA/mois)</strong> pour accéder aux coordonnées de ce talent et le contacter directement.
        </p>
      </div>

      <h3 style="font-size:1rem;font-weight:700;color:#042C53;margin:0 0 16px">Ce que vous obtenez :</h3>
      <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px">
        @foreach(['Accès aux coordonnées directes (email, téléphone)','Messagerie directe avec le talent','Vues illimitées de tous les profils Premium','Badge recruteur vérifié'] as $f)
          <li style="font-size:13.5px;color:#475569;display:flex;align-items:flex-start;gap:8px">
            <span style="color:#38A169;font-weight:700;flex-shrink:0">✓</span> {{ $f }}
          </li>
        @endforeach
      </ul>

      @auth
        <a href="{{ route('recruteur.abonnement') }}" style="display:block;text-align:center;padding:13px 24px;background:#F5C842;border-radius:10px;font-weight:800;font-size:15px;color:#042C53;text-decoration:none">
          ★ Souscrire au Premium Recruteur
        </a>
        <p style="font-size:12px;color:#94a3b8;text-align:center;margin:10px 0 0">À partir de 30 300 FCFA/mois · Accès à toute la ProfilThèque</p>
      @else
        <a href="{{ route('auth.inscription') }}" style="display:block;text-align:center;padding:13px 24px;background:#F5C842;border-radius:10px;font-weight:800;font-size:15px;color:#042C53;text-decoration:none">
          ★ Créer un compte recruteur
        </a>
        <p style="font-size:12px;color:#94a3b8;text-align:center;margin:10px 0 0">Inscrivez-vous gratuitement, puis souscrivez au Premium</p>
      @endauth
    </div>
  </div>
</section>
@endsection
