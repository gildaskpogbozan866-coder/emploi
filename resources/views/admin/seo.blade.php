@extends('layouts.admin')
@section('title', 'SEO & Référencement')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>SEO &amp; Référencement</h1>
    <p>Optimisez la visibilité de la plateforme sur les moteurs de recherche</p>
  </div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('sitemap') }}" target="_blank" rel="noopener"
       class="adm-btn adm-btn--outline adm-btn--sm" style="display:inline-flex;align-items:center;gap:6px">
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
      sitemap.xml
    </a>
    <a href="{{ route('robots') }}" target="_blank" rel="noopener"
       class="adm-btn adm-btn--outline adm-btn--sm" style="display:inline-flex;align-items:center;gap:6px">
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
      robots.txt
    </a>
  </div>
</div>

{{-- Onglets --}}
<div class="adm-tabs" style="margin-bottom:24px">
  <button class="adm-tab active" id="btn-tab-global" onclick="showTab('tab-global')">Paramètres globaux</button>
  <button class="adm-tab" id="btn-tab-pages" onclick="showTab('tab-pages')">SEO par page</button>
</div>

{{-- ── TAB 1 : GLOBAL ── --}}
<div id="tab-global" class="adm-tab-panel active">
  <div class="adm-card" style="max-width:680px">
    <div class="adm-card__header"><h2>Paramètres SEO globaux</h2></div>
    <div class="adm-card__body">

      @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;margin-bottom:20px;color:#15803d;font-size:13.5px">
          {{ session('success') }}
        </div>
      @endif

      <form method="POST" action="{{ route('admin.seo.global.update') }}">
        @csrf @method('PUT')

        <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
          <h3 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 14px">Google Analytics 4</h3>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Measurement ID</label>
            <input type="text" name="ga_measurement_id" value="{{ $global['ga_measurement_id'] }}"
                   placeholder="G-XXXXXXXXXX"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13.5px;box-sizing:border-box;font-family:monospace">
            <p style="font-size:12px;color:#94a3b8;margin:5px 0 0">Le tracking sera injecté automatiquement sur toutes les pages publiques. Laisser vide pour désactiver.</p>
          </div>
        </div>

        <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
          <h3 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 14px">Google Search Console</h3>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Code de vérification HTML</label>
            <input type="text" name="gsc_verification" value="{{ $global['gsc_verification'] }}"
                   placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box;font-family:monospace">
            <p style="font-size:12px;color:#94a3b8;margin:5px 0 0">Contenu de la meta <code>google-site-verification</code> (sans les balises). Trouvable dans Search Console → Paramètres → Propriété.</p>
          </div>
        </div>

        <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
          <h3 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 14px">Image Open Graph par défaut</h3>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">URL de l'image (1200×630 px recommandé)</label>
            <input type="text" name="og_image_default" value="{{ $global['og_image_default'] }}"
                   placeholder="https://emploibouge.bj/images/og-default.jpg"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13.5px;box-sizing:border-box">
            <p style="font-size:12px;color:#94a3b8;margin:5px 0 0">Utilisée quand une page ne définit pas sa propre image OG.</p>
          </div>
        </div>

        <div style="margin-bottom:24px">
          <h3 style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 14px">robots.txt — Lignes supplémentaires</h3>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Règles additionnelles (optionnel)</label>
            <textarea name="robots_txt_extra" rows="4"
                      placeholder="# Exemple : bloquer un robot spécifique&#10;User-agent: BadBot&#10;Disallow: /"
                      style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box;font-family:monospace;resize:vertical">{{ $global['robots_txt_extra'] }}</textarea>
            <p style="font-size:12px;color:#94a3b8;margin:5px 0 0">Ces lignes seront ajoutées à la fin du robots.txt généré automatiquement. <a href="{{ route('robots') }}" target="_blank" style="color:#185FA5">Voir le fichier actuel →</a></p>
          </div>
        </div>

        <button type="submit" class="adm-btn adm-btn--primary">Enregistrer</button>
      </form>
    </div>
  </div>

  {{-- Preview Google Search --}}
  <div class="adm-card" style="max-width:680px;margin-top:20px">
    <div class="adm-card__header"><h2>Liens utiles</h2></div>
    <div class="adm-card__body" style="display:flex;flex-wrap:wrap;gap:12px">
      <a href="https://search.google.com/search-console" target="_blank" rel="noopener"
         style="display:flex;align-items:center;gap:8px;padding:10px 16px;border:1.5px solid #e2e8f0;border-radius:8px;text-decoration:none;color:#374151;font-size:13px;font-weight:500">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4285F4" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        Google Search Console
      </a>
      <a href="https://analytics.google.com" target="_blank" rel="noopener"
         style="display:flex;align-items:center;gap:8px;padding:10px 16px;border:1.5px solid #e2e8f0;border-radius:8px;text-decoration:none;color:#374151;font-size:13px;font-weight:500">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FF6D00" stroke-width="2"><path d="M3 3v18h18"/><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/></svg>
        Google Analytics
      </a>
      <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener"
         style="display:flex;align-items:center;gap:8px;padding:10px 16px;border:1.5px solid #e2e8f0;border-radius:8px;text-decoration:none;color:#374151;font-size:13px;font-weight:500">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34A853" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        PageSpeed Insights
      </a>
      <a href="https://validator.schema.org/" target="_blank" rel="noopener"
         style="display:flex;align-items:center;gap:8px;padding:10px 16px;border:1.5px solid #e2e8f0;border-radius:8px;text-decoration:none;color:#374151;font-size:13px;font-weight:500">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
        Validateur Schema.org
      </a>
    </div>
  </div>
</div>

{{-- ── TAB 2 : PAR PAGE ── --}}
<div id="tab-pages" class="adm-tab-panel">
  <div style="display:flex;flex-direction:column;gap:16px">
    @foreach($pages as $page)
    <div class="adm-card" id="page-{{ $page->page_slug }}">
      <div class="adm-card__header" style="cursor:pointer" onclick="togglePage('{{ $page->page_slug }}')">
        <div style="display:flex;align-items:center;gap:12px">
          <span style="font-size:13px;font-weight:700;color:#042C53">{{ ucfirst($page->page_slug) }}</span>
          @if($page->noindex)
            <span style="background:#fee2e2;color:#b91c1c;font-size:11px;font-weight:700;padding:2px 8px;border-radius:4px">NOINDEX</span>
          @endif
          @if($page->meta_title)
            <span style="font-size:12.5px;color:#64748b;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $page->meta_title }}</span>
          @else
            <span style="font-size:12.5px;color:#cbd5e1;font-style:italic">Aucun titre défini</span>
          @endif
        </div>
        <svg class="adm-nav__chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>

      <div id="body-{{ $page->page_slug }}" style="display:none">
        <div class="adm-card__body">

          {{-- Google preview --}}
          @if($page->meta_title || $page->meta_description)
          <div style="background:#f8f9fa;border:1px solid #e2e8f0;border-radius:8px;padding:14px 16px;margin-bottom:18px">
            <p style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 8px">Aperçu Google</p>
            <div style="font-size:13px;color:#1a0dab;font-weight:400;margin-bottom:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
              {{ $page->meta_title ?? config('app.name') }}
            </div>
            <div style="font-size:12px;color:#006621;margin-bottom:3px">
              {{ url('/') }}/{{ $page->page_slug === 'home' ? '' : $page->page_slug }}
            </div>
            <div style="font-size:13px;color:#545454;line-height:1.4">
              {{ $page->meta_description ? Str::limit($page->meta_description, 160) : '—' }}
            </div>
          </div>
          @endif

          <form method="POST" action="{{ route('admin.seo.page.update', $page) }}">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
              <div>
                <label style="display:block;font-size:12.5px;font-weight:600;color:#374151;margin-bottom:5px">
                  Balise title <span style="font-weight:400;color:#94a3b8">(max 60 car.)</span>
                </label>
                <input type="text" name="meta_title" value="{{ $page->meta_title }}" maxlength="200"
                       style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:7px;font-size:13px;box-sizing:border-box">
              </div>
              <div>
                <label style="display:block;font-size:12.5px;font-weight:600;color:#374151;margin-bottom:5px">
                  Titre OG / Réseaux sociaux
                </label>
                <input type="text" name="og_title" value="{{ $page->og_title }}" maxlength="200"
                       placeholder="Laisser vide pour utiliser le meta title"
                       style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:7px;font-size:13px;box-sizing:border-box">
              </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
              <div>
                <label style="display:block;font-size:12.5px;font-weight:600;color:#374151;margin-bottom:5px">
                  Meta description <span style="font-weight:400;color:#94a3b8">(max 160 car.)</span>
                </label>
                <textarea name="meta_description" rows="3" maxlength="500"
                          style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:7px;font-size:13px;box-sizing:border-box;resize:vertical">{{ $page->meta_description }}</textarea>
              </div>
              <div>
                <label style="display:block;font-size:12.5px;font-weight:600;color:#374151;margin-bottom:5px">
                  Description OG / Réseaux sociaux
                </label>
                <textarea name="og_description" rows="3" maxlength="500"
                          placeholder="Laisser vide pour utiliser la meta description"
                          style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:7px;font-size:13px;box-sizing:border-box;resize:vertical">{{ $page->og_description }}</textarea>
              </div>
            </div>

            <div style="margin-bottom:16px">
              <label style="display:block;font-size:12.5px;font-weight:600;color:#374151;margin-bottom:5px">
                Image OG (URL)
              </label>
              <input type="text" name="og_image_url" value="{{ $page->og_image_url }}" maxlength="500"
                     placeholder="https://... (laissez vide pour utiliser l'image globale)"
                     style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:7px;font-size:13px;box-sizing:border-box">
            </div>

            <div style="display:flex;gap:20px;margin-bottom:18px">
              <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:500;color:#374151">
                <input type="checkbox" name="noindex" value="1" {{ $page->noindex ? 'checked' : '' }}
                       style="accent-color:#dc2626;width:15px;height:15px">
                <span>noindex <span style="font-weight:400;color:#94a3b8">(exclure des résultats Google)</span></span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:500;color:#374151">
                <input type="checkbox" name="nofollow" value="1" {{ $page->nofollow ? 'checked' : '' }}
                       style="accent-color:#dc2626;width:15px;height:15px">
                <span>nofollow <span style="font-weight:400;color:#94a3b8">(ne pas suivre les liens)</span></span>
              </label>
            </div>

            <button type="submit" class="adm-btn adm-btn--primary adm-btn--sm">Enregistrer cette page</button>
          </form>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection

@section('scripts')
<script>
function showTab(id) {
  document.querySelectorAll('.adm-tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.adm-tab').forEach(b => b.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  document.getElementById('btn-' + id).classList.add('active');
}

function togglePage(slug) {
  const body = document.getElementById('body-' + slug);
  body.style.display = body.style.display === 'none' ? 'block' : 'none';
}

// Auto-open first page on tab-pages load
document.getElementById('btn-tab-pages').addEventListener('click', function() {
  const first = document.querySelector('.adm-tab-panel#tab-pages [id^="body-"]');
  if (first && first.style.display === 'none') first.style.display = 'block';
});

// Open relevant section if there's a success flash on the pages tab
@if(session('success') && str_contains(session('success'), 'page'))
  setTimeout(() => document.getElementById('btn-tab-pages')?.click(), 100);
@endif
</script>
@endsection
