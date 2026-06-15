@extends('layouts.app')
@section('title', 'À propos | Emploi Bouge Bénin, la plateforme emploi #1 au Bénin')
@section('description', 'Emploi Bouge Bénin connecte candidats, recruteurs et freelances au Bénin. Découvrez notre mission : faciliter l\'accès à l\'emploi à Cotonou et dans tout le Bénin.')
@section('canonical', route('a-propos'))

@section('content')

{{-- Hero --}}
<section style="background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);padding:72px 20px 64px;text-align:center">
  <span style="display:inline-block;background:rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;padding:4px 14px;border-radius:99px;margin-bottom:20px">À propos</span>
  <h1 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:800;color:#fff;margin:0 0 16px;line-height:1.2">Le pont entre les talents<br>et les opportunités au Bénin</h1>
  <p style="font-size:15px;color:rgba(255,255,255,.8);max-width:560px;margin:0 auto 32px;line-height:1.7">
    Emploi Bouge Bénin est une plateforme qui met en relation candidats, recruteurs, freelances et annonceurs, simplement, rapidement, efficacement.
  </p>
  <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
    <a href="{{ route('offre.list') }}" style="padding:11px 24px;background:#fff;color:#042C53;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">Voir les offres</a>
    <a href="{{ route('contact') }}" style="padding:11px 24px;background:rgba(255,255,255,.15);color:#fff;border:1.5px solid rgba(255,255,255,.3);border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">Nous contacter</a>
  </div>
</section>

{{-- Mission + chiffres --}}
<section style="padding:64px 20px;max-width:1000px;margin:0 auto">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center">
    <div>
      <span style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#185FA5">Notre mission</span>
      <h2 style="font-size:1.75rem;font-weight:800;color:#042C53;margin:10px 0 16px;line-height:1.25">Rendre l'emploi accessible à tous</h2>
      <p style="font-size:14.5px;color:#475569;line-height:1.75;margin:0 0 16px">
        Fondée par <strong>Gildas Hyacinthe KPOGBOZAN</strong>, la plateforme a été créée pour réduire le chômage des jeunes au Bénin en offrant un espace centralisé : offres vérifiées, CV en ligne, services professionnels et espaces publicitaires.
      </p>
      <p style="font-size:14.5px;color:#475569;line-height:1.75;margin:0">
        Notre conviction : chaque talent mérite d'être vu, chaque recruteur mérite de trouver la bonne personne.
      </p>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
      @foreach([['3 rôles','Candidat, Recruteur, Talent'],['100%','Offres vérifiées'],['24/7','Plateforme disponible'],['Gratuit','Inscription ouverte']] as [$n,$l])
      <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:20px 16px;text-align:center">
        <div style="font-size:1.5rem;font-weight:800;color:#185FA5">{{ $n }}</div>
        <div style="font-size:12px;color:#64748b;margin-top:4px">{{ $l }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Valeurs --}}
<section style="background:#f8fafc;padding:56px 20px">
  <div style="max-width:900px;margin:0 auto">
    <p style="text-align:center;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#185FA5;margin:0 0 10px">Ce qui nous guide</p>
    <h2 style="text-align:center;font-size:1.5rem;font-weight:800;color:#042C53;margin:0 0 36px">Nos valeurs</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px">
      @foreach([
        ['M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'Excellence', 'Qualité dans chaque service proposé'],
        ['M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z', 'Accessibilité', "L'emploi sans barrière géographique ni sociale"],
        ['M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'Innovation', 'Des outils modernes adaptés au marché béninois'],
        ['M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'Communauté', 'Un écosystème solidaire pour grandir ensemble'],
      ] as [$icon, $titre, $texte])
      <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:24px 20px">
        <div style="width:40px;height:40px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:14px">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
        </div>
        <h3 style="font-size:14px;font-weight:700;color:#042C53;margin:0 0 6px">{{ $titre }}</h3>
        <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0">{{ $texte }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Fondateur --}}
<section style="padding:64px 20px;max-width:760px;margin:0 auto;text-align:center">
  <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#185FA5;margin:0 0 10px">Notre fondateur</p>
  <div style="width:80px;height:80px;border-radius:50%;background:#e0eaf5;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;overflow:hidden">
    @if(file_exists(public_path('images/fondateur.jpg')))
      <img src="{{ asset('images/fondateur.jpg') }}" alt="Gildas Hyacinthe KPOGBOZAN" style="width:100%;height:100%;object-fit:cover">
    @else
      <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
    @endif
  </div>
  <h2 style="font-size:1.3rem;font-weight:800;color:#042C53;margin:0 0 4px">Gildas Hyacinthe KPOGBOZAN</h2>
  <p style="font-size:13px;color:#185FA5;font-weight:600;margin:0 0 16px">CEO &amp; Fondateur</p>
  <p style="font-size:14.5px;color:#475569;line-height:1.75;margin:0 0 24px;max-width:580px;margin-left:auto;margin-right:auto">
    Jeune entrepreneur béninois passionné par l'emploi et l'éducation, il a bâti Emploi Bouge Bénin pour offrir à chaque jeune les outils nécessaires pour réussir, en ville ou en zone rurale, débutant ou expérimenté.
  </p>
  <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
    <a href="{{ route('contact') }}" style="padding:10px 22px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">Nous contacter</a>
    <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank" rel="noopener noreferrer"
       style="padding:10px 22px;background:#25D366;color:#fff;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">Notre chaîne WhatsApp</a>
  </div>
</section>

@endsection
