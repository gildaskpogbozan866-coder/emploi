@extends('layouts.app')
@section('title', 'Tarifs Talents — Emploi Bouge Bénin')

@section('content')
<section style="padding:64px 20px;background:#f8fafc;min-height:60vh">
  <div style="max-width:860px;margin:0 auto">
    <div style="text-align:center;margin-bottom:48px">
      <span style="background:#38A169;color:#fff;font-size:12px;font-weight:800;padding:4px 16px;border-radius:20px;text-transform:uppercase;letter-spacing:.06em">Espace Talent</span>
      <h1 style="font-size:2.2rem;font-weight:800;color:#042C53;margin:16px 0 12px">Valorisez vos compétences</h1>
      <p style="font-size:1.05rem;color:#64748b;max-width:560px;margin:0 auto;line-height:1.65">
        Créez votre profil Talent et soyez contacté directement par des recruteurs — sans diplôme requis.
      </p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-bottom:48px">
      {{-- Gratuit --}}
      <div style="background:#fff;border:2px solid #e2e8f0;border-radius:18px;padding:32px;display:flex;flex-direction:column">
        <h3 style="font-size:1.1rem;font-weight:700;color:#042C53;margin:0 0 8px">Profil Gratuit</h3>
        <p style="font-size:2rem;font-weight:800;color:#94a3b8;margin:0 0 4px">0 FCFA</p>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 20px">Pour toujours</p>
        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;flex:1">
          @foreach(['Profil visible dans la ProfilThèque','Jusqu\'à 10 vues par mois','Messagerie avec les recruteurs'] as $f)
            <li style="font-size:13.5px;color:#475569;display:flex;align-items:flex-start;gap:8px">
              <span style="color:#38A169;font-weight:700;flex-shrink:0">✓</span> {{ $f }}
            </li>
          @endforeach
        </ul>
        @auth
          <a href="{{ route('talent.dashboard') }}" style="display:block;text-align:center;padding:11px 20px;border:1.5px solid #cbd5e0;border-radius:8px;font-weight:700;font-size:14px;color:#475569;text-decoration:none">
            Mon espace Talent
          </a>
        @else
          <a href="{{ route('auth.inscription') }}" style="display:block;text-align:center;padding:11px 20px;border:1.5px solid #cbd5e0;border-radius:8px;font-weight:700;font-size:14px;color:#475569;text-decoration:none">
            Créer un profil gratuit
          </a>
        @endauth
      </div>

      {{-- Premium --}}
      <div style="background:linear-gradient(135deg,#021e3a 0%,#185FA5 100%);border:2px solid transparent;border-radius:18px;padding:32px;display:flex;flex-direction:column;position:relative">
        <div style="position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:#F5C842;color:#042C53;font-size:11px;font-weight:800;padding:4px 18px;border-radius:20px;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap">Recommandé</div>
        <h3 style="font-size:1.1rem;font-weight:700;color:#fff;margin:0 0 8px">Profil Premium ★</h3>
        <p style="font-size:2rem;font-weight:800;color:#F5C842;margin:0 0 4px">3 000 FCFA</p>
        <p style="font-size:12px;color:rgba(255,255,255,.6);margin:0 0 20px">par mois (30 jours)</p>
        <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;flex:1">
          @foreach(['Profil en tête des résultats de recherche','Coordonnées directement visibles par les recruteurs','Badge Premium ★ valorisant','Vues illimitées','Priorité dans les notifications recruteurs'] as $f)
            <li style="font-size:13.5px;color:rgba(255,255,255,.85);display:flex;align-items:flex-start;gap:8px">
              <span style="color:#F5C842;font-weight:700;flex-shrink:0">✓</span> {{ $f }}
            </li>
          @endforeach
        </ul>
        @auth
          <a href="{{ route('talent.abonnement') }}" style="display:block;text-align:center;padding:11px 20px;background:#F5C842;border-radius:8px;font-weight:800;font-size:14px;color:#042C53;text-decoration:none">
            ★ Passer au Premium — 3 000 FCFA
          </a>
        @else
          <a href="{{ route('auth.inscription') }}" style="display:block;text-align:center;padding:11px 20px;background:#F5C842;border-radius:8px;font-weight:800;font-size:14px;color:#042C53;text-decoration:none">
            ★ S'inscrire et passer au Premium
          </a>
        @endauth
      </div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px 28px;text-align:center">
      <h3 style="font-size:1rem;font-weight:700;color:#042C53;margin:0 0 8px">Déjà inscrit en tant que recruteur ?</h3>
      <p style="font-size:13.5px;color:#64748b;margin:0 0 16px">Découvrez tous les profils Talents sur la ProfilThèque.</p>
      <a href="{{ route('talent.public.list') }}" style="padding:10px 24px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">
        Explorer les Talents
      </a>
    </div>
  </div>
</section>
@endsection
