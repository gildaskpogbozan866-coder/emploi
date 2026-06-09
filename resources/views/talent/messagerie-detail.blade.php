@extends('layouts.dashboard')
@section('title', 'Conversation — Talent')
@section('space-label', 'Espace Talent')

@section('sidebar')
<a href="{{ route('home') }}" class="dash-sidebar__logo">
  <span>Emploi Bouge</span><small>Bénin · Talent</small>
</a>
<ul class="dash-nav">
  <li class="dash-nav__item"><a href="{{ route('talent.dashboard') }}">Tableau de bord</a></li>
  <li class="dash-nav__item"><a href="{{ route('talent.profil') }}">Mon profil</a></li>
  <li class="dash-nav__item active"><a href="{{ route('talent.messagerie') }}">Messagerie</a></li>
  <li class="dash-nav__item"><a href="{{ route('talent.abonnement') }}">Abonnement Premium</a></li>
  <li class="dash-nav__item"><a href="{{ route('talent.parametres') }}">Paramètres</a></li>
</ul>
@endsection

@section('content')
<div class="dash-content">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
    <a href="{{ route('talent.messagerie') }}" style="color:#185FA5;text-decoration:none;font-size:13px"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour</a>
    <div style="width:42px;height:42px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;font-weight:800;color:#065f46">
      {{ strtoupper(substr($autre->prenom ?? '?', 0, 1)) }}
    </div>
    <div>
      <p style="font-weight:700;color:#042C53;margin:0;font-size:15px">{{ $autre->nom_complet }}</p>
      <p style="font-size:12px;color:#94a3b8;margin:0">{{ $autre->entreprise ?? ucfirst($autre->role) }}</p>
    </div>
  </div>

  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px;min-height:300px;max-height:500px;overflow-y:auto;display:flex;flex-direction:column;gap:12px;margin-bottom:16px">
    @forelse($messages as $msg)
      @php $isMine = $msg->expediteur_id === auth()->id(); @endphp
      <div style="display:flex;flex-direction:column;align-items:{{ $isMine ? 'flex-end' : 'flex-start' }}">
        <div style="background:{{ $isMine ? '#185FA5' : '#f1f5f9' }};color:{{ $isMine ? '#fff' : '#1e293b' }};padding:10px 16px;border-radius:{{ $isMine ? '14px 14px 4px 14px' : '14px 14px 14px 4px' }};max-width:72%;font-size:13.5px;line-height:1.55">
          {{ $msg->contenu }}
        </div>
        <p style="font-size:11px;color:#94a3b8;margin:3px 8px 0">{{ $msg->created_at->format('d/m/Y H:i') }}</p>
      </div>
    @empty
      <p style="color:#94a3b8;text-align:center;margin:auto">Démarrez la conversation…</p>
    @endforelse
  </div>

  <form method="POST" action="{{ route('talent.messagerie.store', $conversation) }}" style="display:flex;gap:10px">
    @csrf
    <input type="text" name="contenu" required placeholder="Votre message…"
           style="flex:1;padding:11px 16px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px">
    <button type="submit" style="padding:11px 20px;background:#185FA5;color:#fff;border:none;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer">
      Envoyer
    </button>
  </form>
</div>
@endsection
