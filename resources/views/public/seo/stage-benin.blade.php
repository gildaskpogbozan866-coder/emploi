@extends('layouts.app')
@section('title', 'Stage au Bénin | Offres de stage à Cotonou & tout le Bénin | Emploi Bouge Bénin')
@section('description', 'Trouvez un stage au Bénin : stages rémunérés, stages de fin d\'études, stages d\'observation à Cotonou et dans tout le Bénin. Candidatez gratuitement sur Emploi Bouge Bénin.')
@section('canonical', route('seo.stage-benin'))

@section('jsonld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {"@@type":"ListItem","position":1,"name":"Accueil","item":"{{ route('home') }}"},
    {"@@type":"ListItem","position":2,"name":"Stage Bénin","item":"{{ route('seo.stage-benin') }}"}
  ]
}
</script>
@endsection

@section('content')
<section style="background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);padding:56px 20px 48px;text-align:center">
  <div style="max-width:760px;margin:0 auto">
    <span style="display:inline-block;background:rgba(245,200,66,.15);color:#F5C842;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:4px 14px;border-radius:99px;margin-bottom:16px">Stages · Bénin</span>
    <h1 style="font-size:clamp(1.6rem,4vw,2.4rem);font-weight:800;color:#fff;margin:0 0 14px;line-height:1.25">Offres de stage au Bénin</h1>
    <p style="font-size:15px;color:rgba(255,255,255,.8);margin:0 0 28px;line-height:1.7">
      Stages rémunérés, stages de fin d'études, stages d'observation, toutes les opportunités de stage à Cotonou et dans tout le Bénin.
    </p>
    <a href="{{ route('offre.list', ['type' => 'Stage']) }}"
       style="display:inline-block;padding:13px 30px;background:#F5C842;color:#042C53;border-radius:9px;font-weight:800;font-size:14px;text-decoration:none">
      Voir toutes les offres de stage →
    </a>
  </div>
</section>

{{-- Offres de stage --}}
<section style="padding:48px 20px;max-width:960px;margin:0 auto">
  <h2 style="font-size:1.3rem;font-weight:800;color:#042C53;margin:0 0 24px">Stages disponibles au Bénin</h2>
  @if($offres->isEmpty())
    <p style="color:#64748b;margin-bottom:20px">Aucune offre de stage disponible actuellement.</p>
  @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-bottom:28px">
      @foreach($offres as $offre)
      <a href="{{ route('offre.detail', $offre) }}" style="display:block;background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:18px 16px;text-decoration:none">
        <div style="font-size:13px;font-weight:700;color:#042C53;margin-bottom:4px">{{ $offre->titre }}</div>
        <div style="font-size:12px;color:#185FA5;font-weight:600;margin-bottom:8px">{{ $offre->entreprise }}</div>
        @if($offre->localisation)<span style="font-size:10.5px;background:#f0fdf4;color:#16a34a;border-radius:99px;padding:2px 9px;font-weight:600">{{ $offre->localisation }}</span>@endif
      </a>
      @endforeach
    </div>
  @endif
  <a href="{{ route('offre.list', ['type' => 'Stage']) }}" style="display:inline-flex;align-items:center;gap:6px;color:#185FA5;font-weight:700;text-decoration:none;font-size:14px">
    Voir toutes les offres de stage <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
  </a>
</section>

{{-- Contenu SEO --}}
<section style="background:#f8fafc;padding:48px 20px">
  <div style="max-width:760px;margin:0 auto">
    <h2 style="font-size:1.2rem;font-weight:800;color:#042C53;margin:0 0 14px">Comment trouver un stage au Bénin ?</h2>
    <p style="font-size:14px;color:#475569;line-height:1.8;margin:0 0 14px">
      Trouver un stage au Bénin est souvent difficile pour les étudiants et jeunes diplômés. <strong>Emploi Bouge Bénin</strong> centralise toutes les offres de stage disponibles à Cotonou, Porto-Novo, Parakou et dans toutes les villes du Bénin.
    </p>
    <p style="font-size:14px;color:#475569;line-height:1.8;margin:0 0 14px">
      Déposez votre CV gratuitement et soyez visible des entreprises qui cherchent des stagiaires. Activez les alertes emploi pour recevoir les nouvelles offres de stage directement.
    </p>
    <h3 style="font-size:1rem;font-weight:700;color:#042C53;margin:20px 0 10px">Types de stages disponibles au Bénin</h3>
    <ul style="font-size:14px;color:#475569;line-height:2;padding-left:20px;margin:0 0 20px">
      <li>Stages de fin d'études (Licence, Master, BTS)</li>
      <li>Stages rémunérés dans les entreprises privées</li>
      <li>Stages d'observation et stages académiques</li>
      <li>Stages dans les ONG et organismes internationaux</li>
      <li>Stages dans les administrations publiques béninoises</li>
    </ul>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
      <a href="{{ route('offre.list', ['type' => 'Stage']) }}" style="padding:10px 22px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">Offres de stage</a>
      <a href="{{ auth()->check() && auth()->user()->hasRole('candidat') ? route('candidat.profil') : route('auth.inscription').'?role=candidat' }}" style="padding:10px 22px;background:#fff;color:#042C53;border:1.5px solid #e2e8f0;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">Déposer mon CV</a>
    </div>
  </div>
</section>
@endsection
