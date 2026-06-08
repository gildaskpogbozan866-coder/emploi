@extends('layouts.app')
@section('title', 'Session expirée — Emploi Bouge Bénin')

@section('content')
<section style="min-height:70vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:40px 20px">
  <div>
    <div style="font-size:5rem;font-weight:800;color:#EBF4FD;line-height:1">419</div>
    <h1 style="font-size:1.8rem;color:#042C53;margin:16px 0 12px">Session expirée</h1>
    <p style="color:#64748b;margin-bottom:32px;max-width:420px;margin-left:auto;margin-right:auto;line-height:1.7">
      Votre session a expiré ou le formulaire a déjà été soumis.<br>
      Retournez en arrière et réessayez.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <button onclick="history.back()" class="btn btn--blue">Retour en arrière</button>
      <a href="{{ route('home') }}" class="btn btn--outline">Accueil</a>
    </div>
  </div>
</section>
@endsection
