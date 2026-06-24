@extends('layouts.app')
@section('title', 'Emploi Cotonou | Offres d\'emploi à Cotonou, Bénin | Emploi Bouge Bénin')
@section('description', 'Trouvez un emploi à Cotonou : CDI, CDD, stages et freelance. Toutes les offres d\'emploi à Cotonou vérifiées et mises à jour quotidiennement sur Emploi Bouge Bénin.')
@section('canonical', route('seo.emploi-cotonou'))

@section('jsonld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {"@@type":"ListItem","position":1,"name":"Accueil","item":"{{ route('home') }}"},
    {"@@type":"ListItem","position":2,"name":"Emploi Cotonou","item":"{{ route('seo.emploi-cotonou') }}"}
  ]
}
</script>
@endsection

@section('content')
<section style="background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);padding:56px 20px 48px;text-align:center">
  <div style="max-width:760px;margin:0 auto">
    <span style="display:inline-block;background:rgba(245,200,66,.15);color:#F5C842;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:4px 14px;border-radius:99px;margin-bottom:16px">Cotonou · Bénin</span>
    <h1 style="font-size:clamp(1.6rem,4vw,2.4rem);font-weight:800;color:#fff;margin:0 0 14px;line-height:1.25">Offres d'emploi à Cotonou</h1>
    <p style="font-size:15px;color:rgba(255,255,255,.8);margin:0 0 28px;line-height:1.7">
      Trouvez votre prochain emploi à Cotonou, CDI, CDD, stages, bourses et freelance.<br>
      Toutes les annonces sont vérifiées et mises à jour quotidiennement.
    </p>
    <a href="{{ route('offre.list', ['localisation' => 'Cotonou']) }}"
       style="display:inline-block;padding:13px 30px;background:#F5C842;color:#042C53;border-radius:9px;font-weight:800;font-size:14px;text-decoration:none">
      Voir toutes les offres à Cotonou →
    </a>
  </div>
</section>

{{-- Offres récentes --}}
<section style="padding:48px 20px;max-width:960px;margin:0 auto">
  <h2 style="font-size:1.3rem;font-weight:800;color:#042C53;margin:0 0 24px">Offres récentes à Cotonou</h2>
  @if($offres->isEmpty())
    <p style="color:#64748b">Aucune offre disponible pour le moment. <a href="{{ route('offre.list') }}" style="color:#185FA5">Voir toutes les offres au Bénin</a></p>
  @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-bottom:28px">
      @foreach($offres as $offre)
      <a href="{{ route('offre.detail', $offre) }}" style="display:block;background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:18px 16px;text-decoration:none;transition:border-color .2s">
        <div style="font-size:13px;font-weight:700;color:#042C53;margin-bottom:4px">{{ $offre->titre }}</div>
        <div style="font-size:12px;color:#185FA5;font-weight:600;margin-bottom:8px">{{ $offre->entreprise }}</div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          @if($offre->type)<span style="font-size:10.5px;background:#eff6ff;color:#1d4ed8;border-radius:99px;padding:2px 9px;font-weight:600">{{ $offre->type }}</span>@endif
          @if($offre->localisation)<span style="font-size:10.5px;background:#f0fdf4;color:#16a34a;border-radius:99px;padding:2px 9px;font-weight:600">{{ $offre->localisation }}</span>@endif
        </div>
      </a>
      @endforeach
    </div>
    <a href="{{ route('offre.list', ['localisation' => 'Cotonou']) }}" style="display:inline-flex;align-items:center;gap:6px;color:#185FA5;font-weight:700;text-decoration:none;font-size:14px">
      Voir toutes les offres à Cotonou <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
  @endif
</section>

{{-- Contenu SEO --}}
<section style="background:#f8fafc;padding:48px 20px">
  <div style="max-width:760px;margin:0 auto">
    <h2 style="font-size:1.2rem;font-weight:800;color:#042C53;margin:0 0 16px">Trouver un emploi à Cotonou avec Emploi Bouge Bénin</h2>
    <p style="font-size:14px;color:#475569;line-height:1.8;margin:0 0 14px">
      Cotonou est la capitale économique du Bénin et concentre la majorité des opportunités d'emploi dans le pays. Sur <strong>Emploi Bouge Bénin</strong>, vous accédez à toutes les offres d'emploi à Cotonou : postes en CDI, CDD, stages rémunérés, bourses d'études et missions freelance.
    </p>
    <p style="font-size:14px;color:#475569;line-height:1.8;margin:0 0 14px">
      Notre plateforme de recrutement référence les annonces des entreprises privées, ONG, organismes publics et startups implantés à Cotonou. Chaque offre est vérifiée avant publication.
    </p>
    <h3 style="font-size:1rem;font-weight:700;color:#042C53;margin:20px 0 10px">Comment postuler à Cotonou ?</h3>
    <ol style="font-size:14px;color:#475569;line-height:2;padding-left:20px;margin:0 0 20px">
      <li>Créez votre compte gratuitement sur Emploi Bouge Bénin</li>
      <li>Déposez votre CV en ligne pour être visible des recruteurs</li>
      <li>Consultez les offres d'emploi à Cotonou et postulez en un clic</li>
      <li>Activez les alertes emploi pour ne manquer aucune opportunité</li>
    </ol>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
      <a href="{{ route('offre.list', ['localisation' => 'Cotonou']) }}" style="padding:10px 22px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">Offres à Cotonou</a>
      <a href="{{ route('cv.public.depot') }}" style="padding:10px 22px;background:#fff;color:#042C53;border:1.5px solid #e2e8f0;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">Déposer mon CV</a>
      <a href="{{ route('offre.list') }}" style="padding:10px 22px;background:#fff;color:#042C53;border:1.5px solid #e2e8f0;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">Toutes les offres au Bénin</a>
    </div>
  </div>
</section>

{{-- Articles liés --}}
@if($articles->isNotEmpty())
<section style="padding:48px 20px;max-width:960px;margin:0 auto">
  <h2 style="font-size:1.2rem;font-weight:800;color:#042C53;margin:0 0 20px">Conseils pour trouver un emploi à Cotonou</h2>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px">
    @foreach($articles as $article)
    <a href="{{ route('blog.detail', $article->slug) }}" style="display:block;background:#fff;border:1.5px solid #e2e8f0;border-radius:12px;padding:18px 16px;text-decoration:none">
      <div style="font-size:13px;font-weight:700;color:#042C53;margin-bottom:6px;line-height:1.4">{{ $article->titre }}</div>
      <div style="font-size:12px;color:#64748b">{{ $article->temps_lecture }} min de lecture</div>
    </a>
    @endforeach
  </div>
</section>
@endif
@endsection
