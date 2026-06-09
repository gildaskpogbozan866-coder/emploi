@extends('layouts.app')
@section('title', $profil->metier.' — Emploi Bouge Bénin')

@section('content')
<section style="padding:48px 20px;background:#f8fafc;min-height:70vh">
  <div style="max-width:820px;margin:0 auto">
    <div style="margin-bottom:20px">
      <a href="{{ route('talent.public.list') }}" style="color:#185FA5;text-decoration:none;font-size:13.5px">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour aux profils Talents
      </a>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:32px;margin-bottom:20px">
      <div style="display:flex;align-items:flex-start;gap:22px;flex-wrap:wrap;margin-bottom:24px">
        <div style="width:88px;height:88px;border-radius:50%;background:{{ $profil->plan === 'premium' ? 'linear-gradient(135deg,#F5C842,#e0a800)' : '#dbeafe' }};display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:{{ $profil->plan === 'premium' ? '#042C53' : '#185FA5' }};flex-shrink:0">
          @if($profil->photo)
            <img src="{{ asset('storage/'.$profil->photo) }}" alt="Photo" style="width:88px;height:88px;border-radius:50%;object-fit:cover">
          @else
            {{ strtoupper(substr($profil->user->prenom ?? '?', 0, 1)) }}
          @endif
        </div>
        <div style="flex:1">
          <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:6px">
            <h1 style="font-size:1.5rem;font-weight:800;color:#042C53;margin:0">{{ $profil->user->prenom }}</h1>
            @if($profil->plan === 'premium')
              <span style="background:#F5C842;color:#042C53;font-size:11px;font-weight:800;padding:3px 12px;border-radius:20px">★ Premium</span>
            @endif
          </div>
          <p style="font-size:1.1rem;font-weight:600;color:#185FA5;margin:0 0 4px">{{ $profil->metier }}</p>
          @if($profil->specialite)
            <p style="font-size:13.5px;color:#64748b;margin:0 0 6px">{{ $profil->specialite }}</p>
          @endif
          <p style="font-size:13px;color:#94a3b8;margin:0">
            📍 {{ $profil->ville ? $profil->ville.', ' : '' }}{{ $profil->pays }}
          </p>
        </div>
        <div style="text-align:center;flex-shrink:0">
          <p style="font-size:1.6rem;font-weight:800;color:#185FA5;margin:0">{{ $profil->vues }}</p>
          <p style="font-size:12px;color:#64748b;margin:3px 0 0">vues</p>
        </div>
      </div>

      @if($profil->bio)
      <div style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #f1f5f9">
        <h4 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 10px">À propos</h4>
        <p style="font-size:14.5px;color:#374151;line-height:1.7;margin:0">{{ $profil->bio }}</p>
      </div>
      @endif

      @if($profil->competences)
      <div style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #f1f5f9">
        <h4 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 10px">Compétences</h4>
        <div style="display:flex;flex-wrap:wrap;gap:8px">
          @foreach(explode(',', $profil->competences) as $comp)
            @if(trim($comp))
            <span style="background:#f1f5f9;color:#374151;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:500">{{ trim($comp) }}</span>
            @endif
          @endforeach
        </div>
      </div>
      @endif

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
        @if($profil->experience)
          <div>
            <h4 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 6px">Expérience</h4>
            <p style="font-size:14px;color:#374151;margin:0">{{ $profil->experience }}</p>
          </div>
        @endif
        @if($profil->langues)
          <div>
            <h4 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 6px">Langues</h4>
            <p style="font-size:14px;color:#374151;margin:0">{{ $profil->langues }}</p>
          </div>
        @endif
      </div>

      @if($profil->plan === 'premium')
        <div style="margin-top:24px;padding-top:22px;border-top:1px solid #f1f5f9">
          <h4 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 10px">Coordonnées</h4>
          <div style="display:flex;gap:12px;flex-wrap:wrap">
            @if($profil->user->email)
              <a href="mailto:{{ $profil->user->email }}" style="padding:9px 18px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> {{ $profil->user->email }}
              </a>
            @endif
            @if($profil->user->tel)
              <a href="tel:{{ $profil->user->tel }}" style="padding:9px 18px;background:#f1f5f9;color:#374151;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.41 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.68 2.81a2 2 0 0 1-.45 2.11L7.91 8.76A16 16 0 0 0 12 12a16 16 0 0 0 3.24 1.91l1.91-1.91a2 2 0 0 1 2.11-.45c.91.32 1.85.55 2.81.68A2 2 0 0 1 24 14.21v2.71Z"/></svg> {{ $profil->user->tel }}
              </a>
            @endif
          </div>
        </div>
      @else
        <div style="margin-top:24px;padding:18px 20px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px">
          <p style="font-size:13px;color:#92400e;margin:0">
            <strong>Coordonnées masquées</strong> — Ce talent n'a pas encore activé son abonnement Premium.
          </p>
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
