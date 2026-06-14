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

      {{-- reCAPTCHA --}}
      <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 6px">Google reCAPTCHA v2</h3>
        <p style="font-size:12.5px;color:#6b7280;margin:0 0 14px">
          Protection anti-spam sur les formulaires de contact et d'inscription.
          Clés disponibles sur <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener" style="color:#185FA5">console.google.com/recaptcha</a>.
          <strong>Non actif en environnement local</strong> — les clés ne prennent effet qu'en production.
        </p>
        <div style="display:flex;flex-direction:column;gap:12px">
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Clé publique (Site Key)</label>
            <input type="text" name="recaptcha_site_key" value="{{ $parametres['recaptcha_site_key'] }}"
                   placeholder="6Lc…"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13.5px;font-family:monospace;box-sizing:border-box">
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Clé secrète (Secret Key)</label>
            <input type="password" name="recaptcha_secret_key" value="{{ $parametres['recaptcha_secret_key'] }}"
                   placeholder="6Lc…"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13.5px;font-family:monospace;box-sizing:border-box">
            <p style="font-size:12px;color:#94a3b8;margin-top:4px">La clé secrète n'est jamais affichée en clair une fois enregistrée.</p>
          </div>
        </div>
      </div>

      {{-- Vérification des recruteurs --}}
      <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 6px">Vérification des recruteurs</h3>
        <p style="font-size:12.5px;color:#6b7280;margin:0 0 14px">
          Si activé, après inscription, les recruteurs devront soumettre leurs documents d'entreprise avant d'accéder au tableau de bord. L'admin les valide manuellement.
        </p>
        <label style="display:flex;align-items:center;gap:14px;cursor:pointer;padding:14px 16px;background:{{ $parametres['recruteur_validation_docs'] ? '#f0fdf4' : '#f8fafc' }};border:1.5px solid {{ $parametres['recruteur_validation_docs'] ? '#bbf7d0' : '#e2e8f0' }};border-radius:10px" id="validLbl">
          <div style="position:relative;width:44px;height:24px;flex-shrink:0">
            <input type="checkbox" name="recruteur_validation_docs" value="1"
                   {{ $parametres['recruteur_validation_docs'] ? 'checked' : '' }}
                   onchange="updateToggle(this)"
                   style="opacity:0;width:0;height:0;position:absolute">
            <span id="validTrack" style="position:absolute;inset:0;border-radius:99px;background:{{ $parametres['recruteur_validation_docs'] ? '#16a34a' : '#cbd5e1' }};transition:background .2s"></span>
            <span id="validThumb" style="position:absolute;top:3px;left:{{ $parametres['recruteur_validation_docs'] ? '23px' : '3px' }};width:18px;height:18px;border-radius:50%;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.2);transition:left .2s"></span>
          </div>
          <div>
            <p id="validTxt" style="font-size:13.5px;font-weight:700;color:{{ $parametres['recruteur_validation_docs'] ? '#16a34a' : '#374151' }};margin:0">
              {{ $parametres['recruteur_validation_docs'] ? 'Soumission de documents activée' : 'Soumission de documents désactivée' }}
            </p>
            <p style="font-size:12px;color:#64748b;margin:2px 0 0">
              {{ $parametres['recruteur_validation_docs'] ? 'Les recruteurs doivent soumettre leurs documents après inscription.' : 'Les recruteurs accèdent directement au tableau de bord après inscription.' }}
            </p>
          </div>
        </label>
      </div>

      <button type="submit" class="adm-btn adm-btn--yellow">Enregistrer les paramètres</button>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
function updateToggle(cb) {
  const on = cb.checked;
  document.getElementById('validTrack').style.background  = on ? '#16a34a' : '#cbd5e1';
  document.getElementById('validThumb').style.left        = on ? '23px' : '3px';
  document.getElementById('validTxt').style.color         = on ? '#16a34a' : '#374151';
  document.getElementById('validTxt').textContent         = on ? 'Soumission de documents activée' : 'Soumission de documents désactivée';
  const lbl = document.getElementById('validLbl');
  lbl.style.background   = on ? '#f0fdf4' : '#f8fafc';
  lbl.style.borderColor  = on ? '#bbf7d0' : '#e2e8f0';
}
</script>
@endsection
