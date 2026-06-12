@extends('layouts.app')
@section('title', $offre->titre . ' — ' . $offre->entreprise . ' | Emploi Bouge Bénin')
@section('description', Str::limit(strip_tags($offre->description ?? ''), 160))
@section('canonical', route('offre.detail', $offre))
@section('og_type', 'article')
@section('og_title', $offre->titre . ' — ' . $offre->entreprise)
@section('og_description', Str::limit(strip_tags($offre->description ?? ''), 160))
@section('og_url', route('offre.detail', $offre))

@section('jsonld')
@php
$jsonld = [
    '@@context'      => 'https://schema.org/',
    '@@type'         => 'JobPosting',
    'title'          => $offre->titre,
    'description'    => strip_tags($offre->description ?? ''),
    'datePosted'     => $offre->created_at->toDateString(),
    'employmentType' => strtoupper(str_replace([' ', '-'], '_', $offre->type ?? 'OTHER')),
    'hiringOrganization' => [
        '@@type' => 'Organization',
        'name'   => $offre->entreprise,
        'sameAs' => route('home'),
    ],
    'jobLocation' => [
        '@@type' => 'Place',
        'address' => [
            '@@type'          => 'PostalAddress',
            'addressLocality' => $offre->localisation ?? 'Bénin',
            'addressCountry'  => 'BJ',
        ],
    ],
];
if ($offre->date_limite) {
    $jsonld['validThrough'] = \Carbon\Carbon::parse($offre->date_limite)->toIso8601String();
}
if ($offre->salaire) {
    $jsonld['baseSalary'] = [
        '@@type'    => 'MonetaryAmount',
        'currency'  => 'XOF',
        'value'     => ['@@type' => 'QuantitativeValue', 'value' => $offre->salaire, 'unitText' => 'MONTH'],
    ];
}
// Replace @@-escaped keys with @ for valid JSON-LD
$jsonOutput = str_replace('"@@', '"@', json_encode($jsonld, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
@endphp
<script type="application/ld+json">{!! $jsonOutput !!}</script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/offre/detail-offre.css') }}">
@endsection

@section('content')
<section class="section" style="padding-top:40px">
  <div class="container">
    <a href="{{ route('offre.list') }}" style="display:inline-flex;align-items:center;gap:6px;color:#185FA5;font-size:.9rem;margin-bottom:24px;text-decoration:none">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour aux offres
    </a>

    <div class="offre-detail-layout">

      {{-- Corps principal --}}
      <div class="offre-detail-main">
        <div class="offre-detail-header">
          <div class="offre-detail-logo">{{ strtoupper(substr($offre->entreprise, 0, 2)) }}</div>
          <div>
            <h1 class="offre-detail-title">{{ $offre->titre }}</h1>
            <p class="offre-detail-company">{{ $offre->entreprise }}</p>
            <div class="offre-detail-tags">
              <span class="tag tag--type">{{ $offre->type }}</span>
              @foreach((array)$offre->secteur as $s)<span class="tag">{{ $s }}</span>@endforeach
              <span class="tag">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ $offre->localisation }}
              </span>
              @if($offre->salaire)<span class="tag tag--green">{{ $offre->salaire }}</span>@endif
            </div>
          </div>
        </div>

        @if($offre->fichier)
        <div class="offre-detail-section">
          <a href="{{ Storage::url($offre->fichier) }}" target="_blank" rel="noopener"
             style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:8px;color:#0284c7;font-weight:600;font-size:14px;text-decoration:none">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            Télécharger l'annonce officielle
          </a>
        </div>
        @endif

        <div class="offre-detail-section">
          <h2>Description du poste</h2>
          @php $descIsHtml = str_starts_with(ltrim($offre->description ?? ''), '<'); @endphp
          <div class="offre-detail-content">{!! $descIsHtml ? $offre->description : nl2br(e($offre->description)) !!}</div>
        </div>

        @if($offre->competences->isNotEmpty())
        <div class="offre-detail-section">
          <h2>Compétences requises</h2>
          <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px">
            @foreach($offre->competences as $comp)
              <span style="background:#dbeafe;color:#1e40af;font-size:13px;font-weight:600;padding:4px 12px;border-radius:20px">{{ $comp->nom }}</span>
            @endforeach
          </div>
        </div>
        @endif

        @if($offre->exigences)
        <div class="offre-detail-section">
          <h2>Exigences</h2>
          <div class="offre-detail-content">{!! nl2br(e($offre->exigences)) !!}</div>
        </div>
        @endif

      </div>

      {{-- Sidebar --}}
      <aside class="offre-detail-aside">
        <div class="offre-aside-card">
          @if($aPostule)
            <div style="background:#e6f9f0;border:1px solid #38A169;border-radius:10px;padding:14px;text-align:center;margin-bottom:16px">
              <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              <p style="color:#276749;font-weight:600;margin-top:6px">Vous avez déjà postulé</p>
            </div>
          @elseif($offre->statut !== 'active')
            <div style="background:#fef9ec;border:1px solid #fde68a;border-radius:10px;padding:12px 14px;text-align:center;margin-bottom:16px">
              <p style="color:#92400e;font-weight:600;font-size:13.5px;margin:0">Cette offre n'est plus disponible</p>
            </div>
          @else
            <a href="{{ route('offre.postuler', $offre) }}" class="btn btn--yellow" style="width:100%;text-align:center;display:block;padding:14px">
              Postuler à cette offre
            </a>
          @endif

          <div class="offre-aside-info">
            <div class="offre-aside-row">
              <span class="offre-aside-label">Type</span>
              <span>{{ $offre->type }}</span>
            </div>
            <div class="offre-aside-row">
              <span class="offre-aside-label">Localisation</span>
              <span>{{ $offre->localisation }}</span>
            </div>
            @if($offre->salaire)
            <div class="offre-aside-row">
              <span class="offre-aside-label">Rémunération</span>
              <span>{{ $offre->salaire }}</span>
            </div>
            @endif
            @if($offre->date_limite)
            <div class="offre-aside-row">
              <span class="offre-aside-label">Date limite</span>
              <span>{{ $offre->date_limite->format('d/m/Y') }}</span>
            </div>
            @endif
            <div class="offre-aside-row">
              <span class="offre-aside-label">Publiée</span>
              <span>{{ $offre->created_at->diffForHumans() }}</span>
            </div>
            <div class="offre-aside-row">
              <span class="offre-aside-label">Vues</span>
              <span>{{ $offre->vues }}</span>
            </div>
          </div>

          @auth
          <form method="POST" action="{{ route('candidat.offres-sauvegardees.toggle', $offre) }}" style="margin-top:12px">
            @csrf
            @if($estSauvegarde)
            <button type="submit" class="btn btn--outline" style="width:100%;color:#dc2626;border-color:#fca5a5">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Retirer des favoris
            </button>
            @else
            <button type="submit" class="btn btn--outline" style="width:100%">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Sauvegarder l'offre
            </button>
            @endif
          </form>
          @endauth
        </div>


        {{-- Partage --}}
        @php
          $shareUrl  = urlencode(route('offre.detail', $offre));
          $shareText = urlencode("Offre d'emploi : {$offre->titre} chez {$offre->entreprise}");
        @endphp
        <div class="offre-aside-card" style="margin-top:12px">
          <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 10px">Partager cette offre</p>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener" title="Partager sur WhatsApp"
               style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;background:#25D366;color:#fff;text-decoration:none">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" title="Partager sur LinkedIn"
               style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;background:#0A66C2;color:#fff;text-decoration:none">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" title="Partager sur Facebook"
               style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;background:#1877F2;color:#fff;text-decoration:none">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener" title="Partager sur X"
               style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;background:#000;color:#fff;text-decoration:none">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <button type="button" id="btn-copy-link" data-url="{{ route('offre.detail', $offre) }}" title="Copier le lien"
                    style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;background:#f1f5f9;border:1.5px solid #e2e8f0;color:#64748b;cursor:pointer">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
            </button>
          </div>
        </div>

      </aside>

    </div>
  </div>
</section>

@if($similaires->isNotEmpty())
<section class="section" style="padding-top:0;padding-bottom:48px">
  <div class="container">
    <h2 style="font-size:1.15rem;font-weight:700;color:#042C53;margin-bottom:20px">Offres similaires</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px">
      @foreach($similaires as $s)
      <a href="{{ route('offre.detail', $s) }}"
         style="display:block;background:#fff;border:1.5px solid #e5e7eb;border-radius:12px;padding:18px 20px;text-decoration:none;transition:border-color .15s,box-shadow .15s"
         onmouseover="this.style.borderColor='#185FA5';this.style.boxShadow='0 4px 12px rgba(24,95,165,.1)'"
         onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
          <div style="width:36px;height:36px;border-radius:8px;background:#e0eaf8;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#185FA5;flex-shrink:0">
            {{ strtoupper(substr($s->entreprise, 0, 2)) }}
          </div>
          <div style="min-width:0">
            <p style="font-weight:700;color:#042C53;font-size:13.5px;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $s->titre }}</p>
            <p style="font-size:12px;color:#64748b;margin:0">{{ $s->entreprise }}</p>
          </div>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:5px">
          <span style="background:#f0f9ff;color:#0284c7;font-size:11.5px;font-weight:600;padding:2px 8px;border-radius:20px">{{ $s->type }}</span>
          <span style="background:#f8fafc;color:#64748b;font-size:11.5px;padding:2px 8px;border-radius:20px">{{ $s->localisation }}</span>
        </div>
        <p style="font-size:11.5px;color:#94a3b8;margin:10px 0 0">{{ $s->created_at->diffForHumans() }}</p>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif
@endsection

@section('scripts')
<script>
document.getElementById('btn-copy-link')?.addEventListener('click', function () {
  navigator.clipboard.writeText(this.dataset.url).then(() => {
    const orig = this.innerHTML;
    this.innerHTML = '<svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
    this.style.background = '#dcfce7';
    this.style.borderColor = '#86efac';
    setTimeout(() => { this.innerHTML = orig; this.style.background = '#f1f5f9'; this.style.borderColor = '#e2e8f0'; }, 2000);
  });
});
</script>
@endsection
