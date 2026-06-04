@extends('layouts.app')
@section('title', 'Accès refusé — Emploi Bouge Bénin')

@section('content')
<section style="min-height:70vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:40px 20px">
  <div>
    <div style="font-size:5rem;font-weight:800;color:#EBF4FD;line-height:1">403</div>
    <h1 style="font-size:1.8rem;color:#042C53;margin:16px 0 12px">Accès non autorisé</h1>
    <p style="color:#64748b;margin-bottom:32px">Vous n'avez pas les droits nécessaires pour accéder à cette page.</p>
    <div style="display:flex;gap:12px;justify-content:center">
      <a href="{{ route('home') }}" class="btn btn--blue">Retour à l'accueil</a>
      @guest
      <a href="{{ route('auth.connexion') }}" class="btn btn--outline">Se connecter</a>
      @endguest
    </div>
  </div>
</section>
@endsection
