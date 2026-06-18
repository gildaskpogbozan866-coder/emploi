@extends('layouts.app')
@section('title', 'Désabonnement newsletter | Emploi Bouge Bénin')

@section('content')
<section class="section" style="min-height:60vh;display:flex;align-items:center">
  <div class="container" style="max-width:520px;text-align:center">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:52px 40px">
      <div style="width:64px;height:64px;background:#FEF3C7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#F59E0B" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
        </svg>
      </div>
      <h1 style="font-size:22px;font-weight:700;color:#042C53;margin:0 0 12px">Désabonnement confirmé</h1>
      <p style="color:#64748b;font-size:15px;line-height:1.6;margin:0 0 24px">
        L'adresse <strong>{{ $subscriber->email }}</strong> a bien été retirée de notre newsletter.
        Vous ne recevrez plus nos e-mails.
      </p>
      <p style="color:#94a3b8;font-size:13px;margin:0 0 32px">
        Vous vous êtes désabonné(e) par erreur ?
        <a href="{{ url('/') }}" style="color:#185FA5;text-decoration:underline">Réinscrivez-vous</a>
        depuis le bas de notre site.
      </p>
      <a href="{{ route('home') }}" class="btn btn--primary" style="display:inline-block">Retourner à l'accueil</a>
    </div>
  </div>
</section>
@endsection
