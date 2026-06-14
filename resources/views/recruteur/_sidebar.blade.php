<a href="{{ route('home') }}" class="rec-sidebar__logo">
  <span>Emploi Bouge</span>
  <small>Bénin · Recruteur</small>
</a>

@php
  $abActif = auth()->user()->abonnementActif()->with('plan')->first();
  $estPremiumRec = $abActif && !($abActif->plan?->is_free ?? true);
@endphp
<div class="rec-sidebar__user">
  <div class="rec-sidebar__avatar">{{ auth()->user()->initiale }}</div>
  <div class="rec-sidebar__info">
    <div class="rec-sidebar__name">{{ auth()->user()->nom_complet }}</div>
    <div class="rec-sidebar__role">{{ auth()->user()->entreprise ?? 'Recruteur' }}</div>
    @if($estPremiumRec)
      <span class="rec-sidebar__badge">
        {{ $abActif->plan->name }}
        <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24" style="display:inline-block;vertical-align:-1px;margin-left:2px"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
      </span>
    @elseif($abActif && $abActif->plan?->is_free)
      <span class="rec-sidebar__badge rec-sidebar__badge--free">{{ $abActif->plan->name }}</span>
    @else
      <span class="rec-sidebar__badge rec-sidebar__badge--free">Sans abonnement</span>
    @endif
  </div>
</div>

@if($estPremiumRec && $abActif->ends_at)
  <div style="margin:0 14px 10px;padding:9px 13px;background:rgba(245,200,66,0.12);border:1px solid rgba(245,200,66,0.3);border-radius:8px">
    <p style="font-size:11px;color:rgba(255,255,255,0.6);margin:0 0 2px;text-transform:uppercase;letter-spacing:.06em;font-weight:700">Plan actif jusqu'au</p>
    <p style="font-size:12.5px;color:#F5C842;font-weight:700;margin:0">{{ $abActif->ends_at->format('d/m/Y') }}
      <span style="font-size:11px;font-weight:400;color:rgba(255,255,255,0.55)"> — {{ $abActif->ends_at->diffForHumans() }}</span>
    </p>
  </div>
@elseif(!$abActif)
  <a href="{{ route('recruteur.abonnement.plans') }}" style="display:block;margin:0 14px 10px;padding:9px 13px;background:rgba(245,200,66,0.1);border:1px dashed rgba(245,200,66,0.4);border-radius:8px;text-decoration:none">
    <p style="font-size:11px;color:rgba(255,255,255,0.55);margin:0 0 2px">Aucun abonnement actif</p>
    <p style="font-size:12px;color:#F5C842;font-weight:700;margin:0">Voir les plans →</p>
  </a>
@endif

<ul class="rec-nav">

  {{-- ── Vue d'ensemble ── --}}
  <li class="rec-nav__section">Vue d'ensemble</li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.dashboard') ? 'active' : '' }}">
    <a href="{{ route('recruteur.dashboard') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
      </svg>
      Tableau de bord
    </a>
  </li>

  {{-- ── Recrutement ── --}}
  <li class="rec-nav__section">Recrutement</li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.offres*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.offres') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
      </svg>
      Mes offres d'emploi
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.candidatures*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.candidatures') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
      Candidatures reçues
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.cvtheque*') && !request()->routeIs('recruteur.cv-credits*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.cvtheque') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      CVthèque
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.cv-credits*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.cv-credits.index') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
      </svg>
      Crédits CVthèque
    </a>
  </li>

  {{-- ── Suivi ── --}}
  <li class="rec-nav__section">Suivi</li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.statistiques*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.statistiques') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
      </svg>
      Statistiques
    </a>
  </li>

  {{-- ── Communication ── --}}
  <li class="rec-nav__section">Communication</li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.messagerie*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.messagerie') }}" style="display:flex;align-items:center;gap:10px">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
      </svg>
      <span style="flex:1">Messagerie</span>
      @if(!empty($messagesNonLus) && $messagesNonLus > 0)
        <span style="background:#e53e3e;color:#fff;border-radius:10px;padding:1px 7px;font-size:11px;font-weight:700;line-height:1.6">{{ $messagesNonLus }}</span>
      @endif
    </a>
  </li>

  {{-- ── Compte & Finances ── --}}
  <hr class="rec-nav__divider">
  <li class="rec-nav__section">Compte &amp; Finances</li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.abonnement*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.abonnement') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
      </svg>
      Mon abonnement
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.paiements*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.paiements') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
      </svg>
      Historique paiements
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.profil*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.profil') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
      </svg>
      Mon profil
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.parametres*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.parametres') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
      </svg>
      Paramètres
    </a>
  </li>

</ul>
