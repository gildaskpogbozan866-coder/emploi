@extends('layouts.dashboard')
@section('title', 'Mon profil Talent')
@section('space-label', 'Espace Talent')

@section('sidebar')
<a href="{{ route('home') }}" class="dash-sidebar__logo">
  <span>Emploi Bouge</span><small>Bénin · Talent</small>
</a>
<div class="dash-sidebar__user">
  <div class="dash-sidebar__avatar">{{ auth()->user()->initiale }}</div>
  <div class="dash-sidebar__info">
    <div class="dash-sidebar__name">{{ auth()->user()->nom_complet }}</div>
    <div class="dash-sidebar__role">{{ auth()->user()->metier ?? 'Talent' }}</div>
  </div>
</div>
<ul class="dash-nav">
  <li class="dash-nav__item {{ request()->routeIs('talent.dashboard') ? 'active' : '' }}">
    <a href="{{ route('talent.dashboard') }}">Tableau de bord</a>
  </li>
  <li class="dash-nav__item active">
    <a href="{{ route('talent.profil') }}">Mon profil</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.messagerie*') ? 'active' : '' }}">
    <a href="{{ route('talent.messagerie') }}">Messagerie</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.abonnement*') ? 'active' : '' }}">
    <a href="{{ route('talent.abonnement') }}">Abonnement Premium</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.parametres*') ? 'active' : '' }}">
    <a href="{{ route('talent.parametres') }}">Paramètres</a>
  </li>
</ul>
@endsection

@section('content')
<div class="dash-content">
  <div class="dash-content__header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
      <h1 class="dash-content__title">Mon profil Talent</h1>
      <p style="color:#6b7a8d;margin:0">Votre profil public visible par les recruteurs</p>
    </div>
    @if($profil)
      <div style="display:flex;gap:10px">
        <a href="{{ route('talent.profil.edit') }}" style="padding:9px 18px;background:#185FA5;color:#fff;border-radius:8px;font-weight:600;font-size:13.5px;text-decoration:none">
          Modifier
        </a>
        <a href="{{ route('talent.public.detail', $profil) }}" target="_blank" style="padding:9px 18px;background:#f1f5f9;color:#374151;border-radius:8px;font-weight:600;font-size:13.5px;text-decoration:none">
          Voir public
        </a>
      </div>
    @endif
  </div>

  @if(!$profil)
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:48px 32px;text-align:center;margin-top:20px">
      <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5" style="margin-bottom:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      <h3 style="font-size:1.1rem;font-weight:700;color:#042C53;margin:0 0 8px">Aucun profil créé</h3>
      <p style="color:#64748b;font-size:13.5px;margin:0 0 20px">Créez votre profil Talent pour être visible par les recruteurs et décrocher des opportunités.</p>
      <a href="{{ route('talent.profil.create') }}" style="padding:11px 24px;background:#F5C842;color:#042C53;border-radius:8px;font-weight:700;font-size:14px;text-decoration:none">
        ✦ Créer mon profil Talent
      </a>
    </div>
  @else
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:28px;margin-top:20px">
      <div style="display:flex;align-items:flex-start;gap:20px;flex-wrap:wrap;margin-bottom:24px">
        <div style="width:80px;height:80px;border-radius:50%;background:{{ $profil->plan === 'premium' ? 'linear-gradient(135deg,#F5C842,#e0a800)' : '#dbeafe' }};display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;color:{{ $profil->plan === 'premium' ? '#042C53' : '#185FA5' }};flex-shrink:0">
          @if($profil->photo)
            <img src="{{ asset('storage/'.$profil->photo) }}" alt="Photo" style="width:80px;height:80px;border-radius:50%;object-fit:cover">
          @else
            {{ auth()->user()->initiale }}
          @endif
        </div>
        <div style="flex:1">
          <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px">
            <h2 style="font-size:1.3rem;font-weight:800;color:#042C53;margin:0">{{ auth()->user()->nom_complet }}</h2>
            @if($profil->plan === 'premium')
              <span style="background:#F5C842;color:#042C53;font-size:11px;font-weight:800;padding:2px 10px;border-radius:20px">★ Premium</span>
            @endif
          </div>
          <p style="font-size:15px;font-weight:600;color:#185FA5;margin:0 0 4px">{{ $profil->metier }}</p>
          @if($profil->specialite)
            <p style="font-size:13px;color:#64748b;margin:0 0 6px">{{ $profil->specialite }}</p>
          @endif
          <p style="font-size:13px;color:#94a3b8;margin:0">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:3px"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $profil->ville ? $profil->ville.', ' : '' }}{{ $profil->pays }}
          </p>
        </div>
        <div style="text-align:right">
          <p style="font-size:11.5px;color:#94a3b8;margin:0 0 4px">Vues du profil</p>
          <p style="font-size:1.8rem;font-weight:800;color:#185FA5;margin:0">{{ $profil->vues }}</p>
        </div>
      </div>

      @if($profil->bio)
      <div style="margin-bottom:20px">
        <h4 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 8px">À propos</h4>
        <p style="font-size:14px;color:#374151;line-height:1.65;margin:0">{{ $profil->bio }}</p>
      </div>
      @endif

      @if($profil->competences)
      <div style="margin-bottom:20px">
        <h4 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 10px">Compétences</h4>
        <div style="display:flex;flex-wrap:wrap;gap:8px">
          @foreach(explode(',', $profil->competences) as $comp)
            @if(trim($comp))
            <span style="background:#f1f5f9;color:#374151;padding:4px 12px;border-radius:20px;font-size:12.5px;font-weight:500">{{ trim($comp) }}</span>
            @endif
          @endforeach
        </div>
      </div>
      @endif

      @if($profil->langues)
      <div>
        <h4 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 10px">Langues</h4>
        <p style="font-size:14px;color:#374151;margin:0">{{ $profil->langues }}</p>
      </div>
      @endif
    </div>

    @if($profil->plan !== 'premium')
    <div style="background:linear-gradient(135deg,#fffbeb,#fef9c3);border:1px solid #fde68a;border-radius:12px;padding:18px 22px;margin-top:16px;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
      <div style="flex:1">
        <p style="font-weight:700;color:#042C53;margin:0 0 4px">Passez au Premium ★ pour 3 000 FCFA/mois</p>
        <p style="font-size:13px;color:#64748b;margin:0">Profil mis en avant + coordonnées visibles + badge Premium</p>
      </div>
      <a href="{{ route('talent.abonnement') }}" style="padding:9px 20px;background:#F5C842;color:#042C53;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none;white-space:nowrap">
        Passer au Premium
      </a>
    </div>
    @endif
  @endif
</div>
@endsection
