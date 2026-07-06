@extends('layouts.recruteur')
@section('title', 'Acheter des CV')

@section('css')
<link rel="stylesheet" href="{{ asset('css/searchable-select.css') }}">
@endsection

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Acheter des CV</h1>
    <p>{{ $cvs->total() }} profil{{ $cvs->total() > 1 ? 's' : '' }} disponible{{ $cvs->total() > 1 ? 's' : '' }} sur la plateforme</p>
  </div>
  <div class="rec-topbar__right" style="display:flex;align-items:center;gap:10px">
    <div style="display:flex;align-items:center;gap:8px;background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:10px;padding:8px 16px">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <span style="font-size:13px;color:#0284c7;font-weight:700">{{ $credits }} crédit{{ $credits > 1 ? 's' : '' }}</span>
    </div>
    <a href="{{ route('recruteur.cv-credits.index') }}" class="rec-btn rec-btn--yellow rec-btn--sm">+ Acheter des crédits</a>
  </div>
</div>

@if($credits <= 0)
<div style="background:#fef3c7;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:10px;margin-bottom:18px">
  <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2" style="flex-shrink:0"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
  <p style="margin:0;font-size:13px;color:#92400e">
    Vous n'avez plus de crédits. <a href="{{ route('recruteur.cv-credits.index') }}" style="color:#92400e;font-weight:700;text-decoration:underline">Achetez un pack</a> pour télécharger des CVs.
  </p>
</div>
@endif

{{-- Stats --}}
<div class="rec-stats" style="margin-bottom:18px">
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div class="rec-stat__val">{{ $cvStats['credits_restants'] }}</div>
    <div class="rec-stat__label">Crédits restants</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--purple">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    </div>
    <div class="rec-stat__val">{{ $cvStats['cvs_consultes'] }}</div>
    <div class="rec-stat__label">CV déjà achetés</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    </div>
    <div class="rec-stat__val">{{ $cvStats['cvs_telecharges'] }}</div>
    <div class="rec-stat__label">CV téléchargés</div>
  </div>
</div>

{{-- Message clair sur ce que "déjà acheté" signifie --}}
<div style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:14px 18px;display:flex;align-items:flex-start;gap:10px;margin-bottom:18px">
  <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
  <p style="margin:0;font-size:13px;color:#1e3a5f;line-height:1.6">
    <strong>À savoir :</strong> ouvrir la fiche complète d'un profil (bouton « Voir profil ») le marque définitivement comme <strong>« déjà acheté »</strong> ci-dessous, même sans télécharger le fichier. Télécharger le CV (PDF/Word) consomme en plus <strong>1 crédit</strong> à chaque fois.
  </p>
</div>

{{-- Historique des achats de crédits --}}
@if($paiementsCredits->isNotEmpty())
<div class="rec-card" style="margin-bottom:18px">
  <div class="rec-card__head">
    <span class="rec-card__title">Historique des achats de crédits</span>
    <a href="{{ route('recruteur.cv-credits.index') }}" class="rec-btn rec-btn--outline rec-btn--sm">Voir tout →</a>
  </div>
  <div class="rec-table-wrap">
    <table class="rec-table">
      <thead>
        <tr><th>Date</th><th>Crédits</th><th>Montant</th><th>Méthode</th></tr>
      </thead>
      <tbody>
        @foreach($paiementsCredits as $p)
        <tr>
          <td style="color:#94a3b8;font-size:12px">{{ $p->created_at->format('d/m/Y') }}</td>
          <td style="font-weight:600;color:#042C53">+{{ $p->credits_cv }} crédit{{ $p->credits_cv > 1 ? 's' : '' }}</td>
          <td>{{ number_format($p->montant, 0, ',', ' ') }} {{ $p->devise }}</td>
          <td><span class="rec-badge rec-badge--blue">{{ ucfirst(str_replace('_',' ',$p->methode)) }}</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

{{-- Onglets : tous les profils / déjà consultés --}}
<div style="display:flex;gap:8px;margin-bottom:18px">
  <a href="{{ route('recruteur.cvtheque', array_merge(request()->except(['vu','page']), [])) }}"
     class="rec-btn {{ request('vu') !== 'deja' ? 'rec-btn--primary' : 'rec-btn--outline' }} rec-btn--sm">Tous les profils</a>
  <a href="{{ route('recruteur.cvtheque', array_merge(request()->except('page'), ['vu' => 'deja'])) }}"
     class="rec-btn {{ request('vu') === 'deja' ? 'rec-btn--primary' : 'rec-btn--outline' }} rec-btn--sm">CV déjà achetés</a>
</div>

{{-- Filtres --}}
<div class="rec-card" style="margin-bottom:18px">
  <div class="rec-card__body" style="padding:16px 22px">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
      <div style="display:flex;flex-direction:column;gap:5px;flex:1;min-width:220px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Recherche</label>
        <div style="display:flex;align-items:center;gap:8px;background:#fff;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre, compétences, secteur…" style="border:none;outline:none;font-family:inherit;font-size:13.5px;color:#042C53;background:transparent;width:100%">
        </div>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:160px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Pays</label>
        <select name="pays" onchange="this.form.submit()" data-searchable style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Tous les pays</option>
          @foreach($paysList->reject(fn($p) => $p === 'Autre') as $p)
            <option value="{{ $p }}" {{ request('pays') === $p ? 'selected' : '' }}>{{ $p }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:180px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Disponibilité</label>
        <select name="disponibilite" onchange="this.form.submit()" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Toutes</option>
          @foreach($disponibilitesList as $d)
            <option value="{{ $d->code }}" {{ request('disponibilite') === $d->code ? 'selected' : '' }}>{{ $d->libelle }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:170px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Secteur</label>
        <select name="secteur" onchange="this.form.submit()" data-searchable style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Tous les secteurs</option>
          @foreach($secteursList as $s)
            <option value="{{ $s->libelle }}" {{ request('secteur') === $s->libelle ? 'selected' : '' }}>{{ $s->libelle }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:150px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Langue</label>
        <select name="langue" onchange="this.form.submit()" data-searchable style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Toutes les langues</option>
          @foreach($languesList as $l)
            <option value="{{ $l->nom }}" {{ request('langue') === $l->nom ? 'selected' : '' }}>{{ $l->nom }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:160px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Métier</label>
        <select name="metier" onchange="this.form.submit()" data-searchable style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Tous les métiers</option>
          @foreach($metiersList as $m)
            <option value="{{ $m->nom }}" {{ request('metier') === $m->nom ? 'selected' : '' }}>{{ $m->nom }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:160px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Niveau d'études</label>
        <select name="niveau_etude" onchange="this.form.submit()" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Tous niveaux</option>
          @foreach($niveauxEtudeList as $ne)
            <option value="{{ $ne->code }}" {{ request('niveau_etude') === $ne->code ? 'selected' : '' }}>{{ $ne->libelle }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:160px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Type de contrat</label>
        <select name="type_contrat" onchange="this.form.submit()" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Tous types</option>
          @foreach($typeContratsList as $tc)
            <option value="{{ $tc->code }}" {{ request('type_contrat') === $tc->code ? 'selected' : '' }}>{{ $tc->libelle }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:150px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Expérience</label>
        <select name="niveau_experience" onchange="this.form.submit()" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Toute expérience</option>
          @foreach($niveauxExpList as $ne)
            <option value="{{ $ne->code }}" {{ request('niveau_experience') === $ne->code ? 'selected' : '' }}>{{ $ne->libelle }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="rec-btn rec-btn--primary">Rechercher</button>
      @if(request()->hasAny(['q','pays','disponibilite','secteur','langue','metier','niveau_etude','type_contrat','niveau_experience']))
        <a href="{{ route('recruteur.cvtheque') }}" class="rec-btn rec-btn--outline">Effacer</a>
      @endif
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">
  @forelse($cvs as $item)
  @php $isDoc = !empty($item->_is_document); @endphp
  <div class="rec-offer-card" style="flex-direction:column;align-items:flex-start;gap:14px">
    <div style="display:flex;gap:12px;align-items:center;width:100%">
      <div style="width:46px;height:46px;border-radius:50%;background:{{ $isDoc ? 'rgba(2,132,199,0.1)' : 'rgba(55,138,221,0.12)' }};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:{{ $isDoc ? '#0284c7' : '#185FA5' }};flex-shrink:0">
        {{ strtoupper(substr($item->candidat->prenom ?? '?', 0, 1)) }}
      </div>
      <div style="flex:1;min-width:0">
        <p style="font-weight:700;color:#042C53;margin:0;font-size:14px">
          {{ $isDoc ? ($item->titre_poste ?? 'Document') : ($item->metier ?? 'Profil candidat') }}
        </p>
      </div>
      <div style="display:flex;flex-direction:column;gap:4px;align-items:flex-end;flex-shrink:0">
        @if($isDoc)
          <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:#f0f9ff;color:#0284c7;border:1px solid #bae6fd;white-space:nowrap">{{ $item->type_label }}</span>
        @else
          @if($item->plan === 'premium')
            <span class="rec-badge rec-badge--yellow">Premium</span>
          @endif
          @if(in_array($item->id, $dejaConsultesIds))
            <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;white-space:nowrap">✓ Déjà acheté</span>
            <span style="font-size:9.5px;color:#94a3b8;white-space:nowrap">le {{ $consultationsDates[$item->id]->format('d/m/Y') }}</span>
          @endif
        @endif
      </div>
    </div>

    <div style="width:100%">
      @if(!$isDoc && $item->disponibilite)
        @php $dispo = $disponibilitesList->firstWhere('code', $item->disponibilite) @endphp
        @if($dispo)
        <div style="display:inline-flex;align-items:center;gap:5px;margin-bottom:6px;background:#f8fafc;border-radius:20px;padding:3px 10px;font-size:11.5px;font-weight:600;color:#475569">
          <span style="width:6px;height:6px;border-radius:50%;background:{{ $dispo->couleur }}"></span>
          {{ $dispo->libelle }}
        </div>
        @endif
      @endif
      @if(!empty($item->ville) || !empty($item->pays))
      <p style="font-size:12.5px;color:#94a3b8;margin:0 0 6px">
        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:3px"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        {{ $item->ville ?? $item->pays }}
      </p>
      @endif
      @if($item->competences)
        <p style="font-size:12.5px;color:#475569;margin:0 0 4px;line-height:1.5">{{ Str::limit($item->competences, 80) }}</p>
      @endif
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;width:100%;gap:8px;border-top:1px solid #f0f2f5;padding-top:12px">
      <span style="font-size:11.5px;color:#94a3b8">
        @if(!$isDoc){{ $item->vues }} vue{{ $item->vues > 1 ? 's' : '' }}@else&nbsp;@endif
      </span>
      <div style="display:flex;gap:8px;align-items:center">
        @if(!$isDoc)
          {{-- Bouton favori (CV uniquement) --}}
          <form method="POST" action="{{ route('recruteur.cvtheque.favoris', $item) }}" style="margin:0">
            @csrf
            @php $isFavori = in_array($item->id, $favorisCvIds); @endphp
            <button type="submit"
                    title="{{ $isFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
                    style="padding:5px 8px;background:{{ $isFavori ? '#fef9c3' : '#f1f5f9' }};border:1.5px solid {{ $isFavori ? '#fde68a' : '#e2e8f0' }};border-radius:6px;cursor:pointer;font-size:14px;line-height:1">
              @if($isFavori)
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
              @else
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              @endif
            </button>
          </form>
          <a href="{{ route('recruteur.cvtheque.show', $item) }}" class="rec-btn rec-btn--outline rec-btn--sm">Voir profil</a>
        @else
          <a href="{{ route('recruteur.cvtheque.document.show', $item->id) }}" class="rec-btn rec-btn--outline rec-btn--sm">Voir document</a>
        @endif
      </div>
    </div>
  </div>
  @empty
    @php
      if (request('metier')) {
        $emptyTitle = 'Aucun profil "' . request('metier') . '" disponible pour l\'instant';
        $emptySub   = 'Ce métier est très demandé sur le marché ! De nouveaux candidats rejoignent la plateforme chaque semaine. Revenez bientôt ou élargissez vos critères.';
      } elseif (request('q')) {
        $emptyTitle = 'Aucun résultat pour « ' . request('q') . ' »';
        $emptySub   = 'Ce profil ou cette compétence n\'est pas encore très représenté(e), mais notre base de CVs grandit chaque jour. Essayez un terme proche.';
      } elseif (request('secteur')) {
        $emptyTitle = 'Aucun profil en ' . request('secteur') . ' pour l\'instant';
        $emptySub   = 'Ce secteur est en plein développement sur Emploi Bouge Bénin. De nouveaux talents de ce domaine nous rejoignent régulièrement. Revenez bientôt !';
      } elseif (request('disponibilite')) {
        $libDispo = $disponibilitesList->firstWhere('code', request('disponibilite'));
        $emptyTitle = 'Aucun candidat "' . ($libDispo?->libelle ?? request('disponibilite')) . '" en ce moment';
        $emptySub   = 'Les disponibilités évoluent régulièrement ! Consultez les autres statuts ou revenez dans quelques jours pour trouver le profil idéal.';
      } elseif (request('pays')) {
        $emptyTitle = 'Aucun candidat au ' . request('pays') . ' pour l\'instant';
        $emptySub   = 'Notre réseau s\'étend rapidement dans ce pays. En attendant, explorez les profils d\'autres pays — beaucoup sont ouverts à la mobilité !';
      } elseif (request('langue')) {
        $emptyTitle = 'Pas encore de profil parlant ' . request('langue');
        $emptySub   = 'Ce profil linguistique est rare et très valorisé ! Il rejoindra bientôt la plateforme. Consultez les profils disponibles en attendant.';
      } elseif (request('niveau_etude')) {
        $libEtude = $niveauxEtudeList->firstWhere('code', request('niveau_etude'));
        $emptyTitle = 'Aucun profil avec ce niveau d\'études pour l\'instant';
        $emptySub   = 'Les candidats de niveau ' . ($libEtude?->libelle ?? '') . ' sont très recherchés et arrivent régulièrement. Modifiez le filtre ou revenez bientôt.';
      } elseif (request('type_contrat')) {
        $libContrat = $typeContratsList->firstWhere('code', request('type_contrat'));
        $emptyTitle = 'Aucun candidat cherchant un ' . ($libContrat?->libelle ?? request('type_contrat')) . ' pour l\'instant';
        $emptySub   = 'De nombreux candidats sont ouverts à plusieurs types de contrats. Publiez votre offre et les bons profils viendront directement à vous !';
      } elseif (request('niveau_experience')) {
        $libExp = $niveauxExpList->firstWhere('code', request('niveau_experience'));
        $emptyTitle = 'Aucun profil avec ce niveau d\'expérience pour l\'instant';
        $emptySub   = 'Les talents de niveau ' . ($libExp?->libelle ?? '') . ' sont très convoités. Publiez une offre attractive et recevez des candidatures directement !';
      } else {
        $emptyTitle = 'La CVthèque se remplit chaque jour !';
        $emptySub   = 'Soyez parmi les premiers recruteurs à découvrir les nouveaux talents béninois. Revenez bientôt ou publiez une offre pour attirer les candidats.';
      }
    @endphp
    <div style="grid-column:1/-1">
      <div class="rec-empty">
        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
        <h3>{{ $emptyTitle }}</h3>
        <p>{{ $emptySub }}</p>
      </div>
    </div>
  @endforelse
</div>

@if($cvs->hasPages())
  <div style="margin-top:28px">{{ $cvs->links() }}</div>
@endif
@endsection

@section('scripts')
<script src="{{ asset('js/searchable-select.js') }}"></script>
@endsection
