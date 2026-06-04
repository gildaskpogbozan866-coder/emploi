@extends('layouts.app')
@section('title', 'Commande confirmée — Emploi Bouge Bénin')

@section('content')
<section style="padding:80px 20px;background:#f8fafc;min-height:70vh;display:flex;align-items:center;justify-content:center">
  <div style="max-width:560px;text-align:center">
    <div style="width:88px;height:88px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
      <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      </svg>
    </div>
    <h1 style="font-size:2rem;font-weight:800;color:#042C53;margin:0 0 14px">Commande reçue !</h1>
    <p style="font-size:1.05rem;color:#64748b;line-height:1.65;margin:0 0 32px">
      Votre demande a bien été enregistrée. Notre équipe va la traiter dans les plus brefs délais et vous contactera par email pour confirmer et vous livrer le résultat.
    </p>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:22px 24px;margin-bottom:28px;text-align:left">
      <div style="display:flex;align-items:flex-start;gap:12px">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2" style="flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <div>
          <p style="font-weight:700;color:#042C53;margin:0 0 4px">Confirmation par email</p>
          <p style="font-size:13.5px;color:#64748b;margin:0">Un email de confirmation vous a été envoyé avec les détails de votre commande.</p>
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="{{ route('service.list') }}" style="padding:11px 24px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:14px;text-decoration:none">
        Découvrir d'autres services
      </a>
      <a href="{{ route('home') }}" style="padding:11px 24px;background:#f1f5f9;color:#374151;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none">
        Retour à l'accueil
      </a>
    </div>
  </div>
</section>
@endsection
