@extends('layouts.app')
@section('title', 'Recrutement Bénin | Trouvez les meilleurs talents béninois | Emploi Bouge Bénin')
@section('description', 'Recrutez au Bénin avec Emploi Bouge Bénin : publiez vos annonces, accédez à notre CVthèque et trouvez les meilleurs candidats à Cotonou et dans tout le Bénin.')
@section('canonical', route('seo.recrutement-benin'))

@section('jsonld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {"@@type":"ListItem","position":1,"name":"Accueil","item":"{{ route('home') }}"},
    {"@@type":"ListItem","position":2,"name":"Recrutement Bénin","item":"{{ route('seo.recrutement-benin') }}"}
  ]
}
</script>
@endsection

@section('content')
<section style="background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);padding:56px 20px 48px;text-align:center">
  <div style="max-width:760px;margin:0 auto">
    <span style="display:inline-block;background:rgba(245,200,66,.15);color:#F5C842;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:4px 14px;border-radius:99px;margin-bottom:16px">Recrutement · Bénin</span>
    <h1 style="font-size:clamp(1.6rem,4vw,2.4rem);font-weight:800;color:#fff;margin:0 0 14px;line-height:1.25">Recrutement au Bénin, Trouvez les meilleurs talents</h1>
    <p style="font-size:15px;color:rgba(255,255,255,.8);margin:0 0 28px;line-height:1.7">
      Publiez vos offres d'emploi, accédez à notre CVthèque et recrutez les profils adaptés à votre entreprise au Bénin.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="{{ route('auth.inscription') }}" style="padding:13px 26px;background:#F5C842;color:#042C53;border-radius:9px;font-weight:800;font-size:14px;text-decoration:none">Publier une offre gratuitement →</a>
      <a href="{{ route('cv.public.theque') }}" style="padding:13px 26px;background:rgba(255,255,255,.12);color:#fff;border:1.5px solid rgba(255,255,255,.3);border-radius:9px;font-weight:700;font-size:14px;text-decoration:none">Consulter les CVs</a>
    </div>
  </div>
</section>

{{-- Avantages recruteurs --}}
<section style="padding:56px 20px;max-width:960px;margin:0 auto">
  <h2 style="font-size:1.3rem;font-weight:800;color:#042C53;margin:0 0 8px;text-align:center">Pourquoi recruter sur Emploi Bouge Bénin ?</h2>
  <p style="text-align:center;color:#64748b;font-size:14px;margin:0 0 32px">La plateforme de recrutement de référence au Bénin</p>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px">
    @foreach([
      ['Diffusion maximale','Vos annonces sont visibles par des milliers de candidats actifs au Bénin.'],
      ['Candidats vérifiés','Accédez à notre CVthèque de talents qualifiés à Cotonou et dans tout le pays.'],
      ['Publication rapide','Publiez votre offre en moins de 5 minutes. Première candidature en 24h.'],
      ['Ciblage précis','Filtrez par localisation, secteur, type de contrat et niveau d\'expérience.'],
    ] as [$titre, $texte])
    <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:22px 18px">
      <h3 style="font-size:14px;font-weight:700;color:#042C53;margin:0 0 6px">{{ $titre }}</h3>
      <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0">{{ $texte }}</p>
    </div>
    @endforeach
  </div>
</section>

{{-- Offres récentes --}}
<section style="background:#f8fafc;padding:48px 20px">
  <div style="max-width:960px;margin:0 auto">
    <h2 style="font-size:1.2rem;font-weight:800;color:#042C53;margin:0 0 20px">Dernières offres publiées au Bénin</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;margin-bottom:24px">
      @foreach($offres as $offre)
      <a href="{{ route('offre.detail', $offre) }}" style="display:block;background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:16px;text-decoration:none">
        <div style="font-size:13px;font-weight:700;color:#042C53;margin-bottom:4px">{{ $offre->titre }}</div>
        <div style="font-size:12px;color:#185FA5;font-weight:600;margin-bottom:8px">{{ $offre->entreprise }}</div>
        @if($offre->type)<span style="font-size:10.5px;background:#eff6ff;color:#1d4ed8;border-radius:99px;padding:2px 9px;font-weight:600">{{ $offre->type?->libelle }}</span>@endif
      </a>
      @endforeach
    </div>
    <a href="{{ route('offre.list') }}" style="color:#185FA5;font-weight:700;text-decoration:none;font-size:14px">Voir toutes les offres →</a>
  </div>
</section>

{{-- Contenu SEO --}}
<section style="padding:48px 20px;max-width:760px;margin:0 auto">
  <h2 style="font-size:1.2rem;font-weight:800;color:#042C53;margin:0 0 14px">Recrutement au Bénin : comment ça fonctionne ?</h2>
  <p style="font-size:14px;color:#475569;line-height:1.8;margin:0 0 14px">
    <strong>Emploi Bouge Bénin</strong> est la plateforme de recrutement qui connecte les entreprises béninoises aux meilleurs talents du pays. Que vous soyez une PME, une multinationale, une ONG ou une startup, publiez vos offres d'emploi gratuitement et recevez des candidatures qualifiées.
  </p>
  <p style="font-size:14px;color:#475569;line-height:1.8;margin:0 0 20px">
    Notre CVthèque regroupe des centaines de profils de candidats vérifiés à Cotonou, Porto-Novo, Parakou et dans tout le Bénin. Les recruteurs peuvent rechercher par compétences, secteur et disponibilité.
  </p>
  <div style="display:flex;gap:12px;flex-wrap:wrap">
    <a href="{{ route('auth.inscription') }}" style="padding:10px 22px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">Créer un compte recruteur</a>
    <a href="{{ route('cv.public.theque') }}" style="padding:10px 22px;background:#fff;color:#042C53;border:1.5px solid #e2e8f0;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">Consulter la CVthèque</a>
  </div>
</section>
@endsection
