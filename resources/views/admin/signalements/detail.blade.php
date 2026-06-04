@extends('layouts.admin')
@section('title', 'Signalement — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.signalements.list') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">← Retour</a>
    <h1>Signalement #{{ $signalement->id }}</h1>
    <p>{{ ucfirst($signalement->type) }} · Reçu le {{ $signalement->created_at->format('d/m/Y à H:i') }}</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:900px">
  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Signalement</h3>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Type</p><p style="font-weight:600;color:#042C53;margin:0">{{ ucfirst($signalement->type) }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Raison</p><p style="font-size:14px;color:#374151;line-height:1.6;margin:0">{{ $signalement->raison }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Statut</p>
        <span class="adm-badge adm-badge--{{ match($signalement->statut) {
          'en_attente' => 'yellow',
          'traite'     => 'green',
          'rejete'     => 'gray',
          default      => 'gray'
        } }}">{{ ucfirst(str_replace('_',' ',$signalement->statut)) }}</span>
      </div>
    </div>
  </div>

  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Signalé par</h3>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Utilisateur</p><p style="font-weight:600;color:#042C53;margin:0">{{ $signalement->user->nom_complet }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Email</p><p style="font-weight:600;color:#042C53;margin:0">{{ $signalement->user->email }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Rôle</p><p style="font-weight:600;color:#042C53;margin:0">{{ ucfirst($signalement->user->role) }}</p></div>
    </div>
  </div>
</div>

<div class="adm-card" style="max-width:900px;margin-top:20px">
  <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Traitement</h3>
  </div>
  <div style="padding:20px 24px">
    <form method="POST" action="{{ route('admin.signalements.statut', $signalement) }}" style="display:flex;flex-direction:column;gap:16px;max-width:480px">
      @csrf @method('PATCH')
      <div>
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Décision</label>
        <select name="statut" class="adm-select" style="min-width:200px">
          <option value="en_attente" {{ $signalement->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
          <option value="traite" {{ $signalement->statut === 'traite' ? 'selected' : '' }}>Traité (contenu supprimé)</option>
          <option value="rejete" {{ $signalement->statut === 'rejete' ? 'selected' : '' }}>Rejeté (signalement non fondé)</option>
        </select>
      </div>
      <div>
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Note administrative</label>
        <textarea name="note_admin" rows="3" placeholder="Commentaire interne sur la décision…"
                  style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ $signalement->note_admin }}</textarea>
      </div>
      <div>
        <button type="submit" class="adm-btn adm-btn--primary">Enregistrer la décision</button>
      </div>
    </form>
  </div>
</div>
@endsection
