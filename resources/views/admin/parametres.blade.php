@extends('layouts.admin')
@section('title', 'Paramètres — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Paramètres du site</h1>
    <p>Configuration générale de la plateforme</p>
  </div>
</div>

<div class="adm-card" style="max-width:640px">
  <div style="padding:24px">

    @if(session('success'))
      <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;font-size:.88rem;color:#065f46;margin-bottom:20px">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.parametres.update') }}">
      @csrf @method('PUT')

      <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 16px">Informations générales</h3>
        <div style="display:flex;flex-direction:column;gap:14px">
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Nom du site</label>
            <input type="text" name="site_nom" value="{{ $parametres['site_nom'] }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Email de contact (expéditeur)</label>
            <input type="email" name="site_email" value="{{ $parametres['site_email'] }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
          </div>
        </div>
      </div>

      <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 6px">Notifications admin</h3>
        <p style="font-size:12.5px;color:#6b7280;margin:0 0 14px">Adresse e-mail qui reçoit les alertes : nouveaux dossiers recruteurs soumis, etc.</p>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">E-mail de réception des alertes</label>
          <input type="email" name="admin_notification_email"
                 value="{{ $parametres['admin_notification_email'] }}"
                 placeholder="ex : notifications@emploibouge.bj"
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('admin_notification_email') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('admin_notification_email')
            <p style="color:#e53e3e;font-size:12px;margin-top:4px">{{ $message }}</p>
          @enderror
          <p style="font-size:12px;color:#94a3b8;margin-top:6px">Laisser vide pour désactiver les notifications par e-mail.</p>
        </div>
      </div>

      <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 16px">Statut du site</h3>
        <div style="display:flex;align-items:center;gap:12px;padding:16px;background:{{ $parametres['maintenance_mode'] ? '#fef2f2' : '#f0fdf4' }};border-radius:10px;border:1px solid {{ $parametres['maintenance_mode'] ? '#fecaca' : '#bbf7d0' }}">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="{{ $parametres['maintenance_mode'] ? '#dc2626' : '#16a34a' }}" stroke-width="2">
            @if($parametres['maintenance_mode'])
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            @else
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @endif
          </svg>
          <div style="flex:1">
            <p style="font-weight:700;color:{{ $parametres['maintenance_mode'] ? '#dc2626' : '#16a34a' }};margin:0 0 2px">
              {{ $parametres['maintenance_mode'] ? 'Mode maintenance actif' : 'Site en ligne' }}
            </p>
            <p style="font-size:12.5px;color:#64748b;margin:0">
              {{ $parametres['maintenance_mode'] ? 'Le site est en maintenance, les visiteurs voient une page d\'erreur 503.' : 'Le site est accessible normalement.' }}
            </p>
          </div>
        </div>
      </div>

      <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 16px">Plans d'abonnement (référence)</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px">
          <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px">
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 4px">Candidat Premium</p>
            <p style="font-size:1.2rem;font-weight:800;color:#185FA5;margin:0">5 000 FCFA</p>
            <p style="font-size:12px;color:#64748b;margin:2px 0 0">/ 30 jours</p>
          </div>
          <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px">
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 4px">Recruteur Premium 30</p>
            <p style="font-size:1.2rem;font-weight:800;color:#185FA5;margin:0">30 300 FCFA</p>
            <p style="font-size:12px;color:#64748b;margin:2px 0 0">30 offres / 30 jours</p>
          </div>
          <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px">
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 4px">Recruteur Premium 50</p>
            <p style="font-size:1.2rem;font-weight:800;color:#185FA5;margin:0">50 500 FCFA</p>
            <p style="font-size:12px;color:#64748b;margin:2px 0 0">50 offres / 30 jours</p>
          </div>
          <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px">
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 4px">Talent Premium</p>
            <p style="font-size:1.2rem;font-weight:800;color:#185FA5;margin:0">3 000 FCFA</p>
            <p style="font-size:12px;color:#64748b;margin:2px 0 0">/ 30 jours</p>
          </div>
        </div>
      </div>

      <button type="submit" class="adm-btn adm-btn--primary">Enregistrer les paramètres</button>
    </form>
  </div>
</div>
@endsection
