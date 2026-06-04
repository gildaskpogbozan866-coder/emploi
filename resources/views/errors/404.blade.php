@extends('layouts.app')
@section('title', 'Page introuvable — Emploi Bouge Bénin')

@section('content')
<section style="min-height:70vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:40px 20px">
  <div>
    <div style="font-size:5rem;font-weight:800;color:#EBF4FD;line-height:1">404</div>
    <h1 style="font-size:1.8rem;color:#042C53;margin:16px 0 12px">Page introuvable</h1>
    <p style="color:#64748b;margin-bottom:32px;max-width:400px;margin-left:auto;margin-right:auto">
      La page que vous cherchez n'existe plus ou a été déplacée.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="{{ route('home') }}" class="btn btn--blue">Retour à l'accueil</a>
      <a href="{{ route('offre.list') }}" class="btn btn--outline">Voir les offres</a>
    </div>
  </div>
</section>
@endsection
