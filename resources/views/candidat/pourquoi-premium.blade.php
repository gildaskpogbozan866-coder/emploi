@extends('layouts.candidat')
@section('title', 'Pourquoi passer Premium ? | Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/candidat/pourquoi-premium.css') }}">
@endsection

@section('sidebar')
    @include('candidat._sidebar')
@endsection

@section('content')

@php $price = $plan_premium?->price ?? '1 500'; @endphp

{{-- HERO --}}
<div class="pp-hero">
    <div class="pp-hero__deco pp-hero__deco--1"></div>
    <div class="pp-hero__deco pp-hero__deco--2"></div>
    <div class="pp-hero__inner">
        <div class="pp-hero__badge">
            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            Premium Candidat
        </div>
        <h1 class="pp-hero__title">Soyez vu.<br>Soyez choisi.</h1>
        <p class="pp-hero__sub">Les recruteurs voient les profils Premium en premier. Pour {{ number_format($price, 0, ',', ' ') }} FCFA/mois, arrêtez d'attendre.</p>
        <div class="pp-hero__price">
            <span class="pp-hero__amount">{{ number_format($price, 0, ',', ' ') }}</span>
            <div class="pp-hero__price-details">
                <span class="pp-hero__currency">FCFA</span>
                <span class="pp-hero__period">/ mois</span>
            </div>
        </div>
        <div class="pp-hero__actions">
            <a href="#payer" class="pp-btn pp-btn--primary">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Activer maintenant
            </a>
            <span class="pp-hero__guarantee">Sans engagement · Résiliable à tout moment</span>
        </div>
    </div>
</div>

{{-- COMPARAISON --}}
<div class="pp-compare">
    <div class="pp-compare__col pp-compare__col--free">
        <div class="pp-compare__head">
            <div class="pp-compare__plan-name">Gratuit</div>
            <div class="pp-compare__plan-price">0 FCFA</div>
        </div>
        <ul class="pp-compare__list">
            @foreach(['1 seul CV', '10 candidatures / mois', 'Profil noyé dans la liste', 'Pas d\'alertes emploi', 'Invisible aux recruteurs'] as $item)
            <li class="pp-compare__item pp-compare__item--no">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ $item }}
            </li>
            @endforeach
        </ul>
    </div>

    <div class="pp-compare__col pp-compare__col--premium">
        <div class="pp-compare__badge">Recommandé</div>
        <div class="pp-compare__head">
            <div class="pp-compare__plan-name">
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                Premium
            </div>
            <div class="pp-compare__plan-price">{{ number_format($price, 0, ',', ' ') }} FCFA<span>/mois</span></div>
        </div>
        <ul class="pp-compare__list">
            @foreach(['Plusieurs CV adaptés par secteur', 'Candidatures illimitées', 'Profil en tête de la CVthèque', 'Alertes emploi en temps réel', 'CV recommandé aux recruteurs'] as $item)
            <li class="pp-compare__item pp-compare__item--yes">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ $item }}
            </li>
            @endforeach
        </ul>
    </div>
</div>

{{-- AVANTAGES --}}
<div class="pp-benefits">
    @foreach([
        ['blue',   'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12',  'Plusieurs CV',           'Un CV par secteur. Adaptez, multipliez vos chances.'],
        ['yellow', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'Profil en vedette',      'Vous apparaissez en premier. Avant tout le monde.'],
        ['green',  'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                    'Candidatures sans limite','Postulez à toutes les offres. Aucun blocage.'],
        ['orange', 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'Alertes instantanées',   'Soyez le premier à postuler, pas le dernier.'],
        ['purple', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'Recommandé aux DRH',    'On vous présente aux entreprises. Elles vous cherchent.'],
        ['teal',   'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'Vues sur votre CV',      'Sachez combien de recruteurs consultent votre profil.'],
    ] as [$color, $icon, $title, $text])
    <div class="pp-benefit">
        <div class="pp-benefit__icon pp-benefit__icon--{{ $color }}">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
            </svg>
        </div>
        <div class="pp-benefit__body">
            <div class="pp-benefit__title">{{ $title }}</div>
            <div class="pp-benefit__text">{{ $text }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- CTA --}}
@if($plan_premium)
<div class="pp-cta-wrap" id="payer">
    <div class="pp-cta">
        <div class="pp-cta__deco"></div>
        <h2 class="pp-cta__title">C'est le prix d'une recharge.<br>C'est peut-être le prix d'un emploi.</h2>
        <div class="pp-cta__price-block">
            <div class="pp-cta__amount">{{ number_format($price, 0, ',', ' ') }}</div>
            <div class="pp-cta__unit">FCFA<br><span>/ mois</span></div>
        </div>
        <form method="POST" action="{{ route('candidat.abonnement.store') }}">
            @csrf
            <input type="hidden" name="plan_id" value="{{ $plan_premium->id }}">
            <button type="submit" class="pp-btn pp-btn--pay">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Payer maintenant — {{ number_format($price, 0, ',', ' ') }} FCFA/mois
            </button>
        </form>
        <div class="pp-cta__guarantees">
            <span>✓ Paiement sécurisé</span>
            <span>✓ Actif immédiatement</span>
            <span>✓ Sans engagement</span>
        </div>
    </div>
</div>
@endif

<div style="text-align:center;padding:8px 0 28px">
    <a href="{{ route('candidat.dashboard') }}" style="font-size:13px;color:#94a3b8;text-decoration:none">← Retour au tableau de bord</a>
</div>

@endsection
