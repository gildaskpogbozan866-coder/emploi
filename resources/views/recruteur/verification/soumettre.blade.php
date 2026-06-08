@extends('layouts.auth')
@section('title', 'Vérification de votre entreprise — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/inscription.css') }}">
<style>
.vf-page { min-height: 100vh; background: #f1f5f9; display: flex; flex-direction: column; align-items: center; padding: 40px 16px 60px; }
.vf-header { text-align: center; margin-bottom: 32px; }
.vf-logo { display: inline-flex; align-items: center; gap: 8px; font-weight: 700; color: #1e3a5f; font-size: .95rem; text-decoration: none; margin-bottom: 20px; }
.vf-logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg,#2563eb,#1d4ed8); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
.vf-steps { display: flex; align-items: center; gap: 0; justify-content: center; margin-bottom: 8px; }
.vf-step { display: flex; align-items: center; gap: 8px; font-size: .82rem; color: #94a3b8; }
.vf-step.active { color: #2563eb; font-weight: 600; }
.vf-step.done { color: #059669; }
.vf-step__num { width: 26px; height: 26px; border-radius: 50%; border: 2px solid currentColor; display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; flex-shrink: 0; }
.vf-step__sep { width: 40px; height: 2px; background: #e2e8f0; margin: 0 4px; }
.vf-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px; margin-bottom: 16px; width: 100%; max-width: 680px; box-sizing: border-box; }
.vf-card__num { width: 32px; height: 32px; border-radius: 50%; background: #2563eb; color: #fff; font-weight: 700; font-size: .88rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vf-card__header { display: flex; align-items: center; gap: 12px; margin-bottom: 6px; }
.vf-card__title { font-weight: 700; color: #111827; font-size: 1rem; }
.vf-card__desc { font-size: .82rem; color: #6b7280; margin: 0 0 20px 44px; }
.vf-ou { display: flex; align-items: center; gap: 12px; margin: 16px 0; }
.vf-ou::before, .vf-ou::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
.vf-ou span { font-size: .75rem; font-weight: 700; color: #94a3b8; letter-spacing: .05em; padding: 4px 10px; border: 1px solid #e2e8f0; border-radius: 20px; background: #f8fafc; }
.vf-upload { border: 2px dashed #cbd5e1; border-radius: 10px; padding: 16px; text-align: center; cursor: pointer; transition: border-color .2s, background .2s; position: relative; }
.vf-upload:hover { border-color: #2563eb; background: #f0f7ff; }
.vf-upload input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; }
.vf-upload__icon { color: #94a3b8; margin-bottom: 6px; }
.vf-upload__text { font-size: .82rem; color: #64748b; }
.vf-upload__text strong { color: #2563eb; }
.vf-upload__hint { font-size: .75rem; color: #94a3b8; margin-top: 4px; }
.vf-field-label { font-size: .83rem; font-weight: 600; color: #374151; display: block; margin-bottom: 8px; }
.vf-input { width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; font-size: .88rem; color: #111827; box-sizing: border-box; transition: border-color .2s; }
.vf-input:focus { outline: none; border-color: #2563eb; }
.vf-error { color: #dc2626; font-size: .8rem; margin-top: 5px; }
.vf-badge-existing { display: inline-flex; align-items: center; gap: 6px; background: #d1fae5; color: #065f46; font-size: .78rem; padding: 4px 10px; border-radius: 20px; margin-bottom: 10px; }
.vf-reject-banner { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 10px; padding: 16px; margin-bottom: 20px; max-width: 680px; width: 100%; box-sizing: border-box; }
.vf-submit-wrap { width: 100%; max-width: 680px; }
.vf-submit { width: 100%; background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; border: none; border-radius: 12px; padding: 16px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: opacity .2s; }
.vf-submit:hover { opacity: .9; }
</style>
@endsection

@section('content')
<div class="vf-page">

  {{-- Logo --}}
  <a href="{{ route('home') }}" class="vf-logo">
    <span class="vf-logo-icon">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
      </svg>
    </span>
    Emploi Bouge Bénin
  </a>

  {{-- En-tête --}}
  <div class="vf-header">
    <div class="vf-steps">
      <div class="vf-step done">
        <div class="vf-step__num">
          <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span>Inscription</span>
      </div>
      <div class="vf-step__sep"></div>
      <div class="vf-step done">
        <div class="vf-step__num">
          <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span>E-mail vérifié</span>
      </div>
      <div class="vf-step__sep"></div>
      <div class="vf-step active">
        <div class="vf-step__num">3</div>
        <span>Vérification entreprise</span>
      </div>
      <div class="vf-step__sep"></div>
      <div class="vf-step">
        <div class="vf-step__num">4</div>
        <span>Accès complet</span>
      </div>
    </div>
    <h1 style="font-size:1.5rem;font-weight:800;color:#111827;margin:16px 0 6px">Vérification de votre entreprise</h1>
    <p style="font-size:.88rem;color:#6b7280;margin:0">Renseignez vos documents ci-dessous. Notre équipe les examinera sous 24–48h.</p>
  </div>

  {{-- Bannière rejet --}}
  @if($verification?->estRejete())
    <div class="vf-reject-banner">
      <div style="font-weight:700;color:#dc2626;font-size:.88rem;margin-bottom:6px">Dossier rejeté — Motif :</div>
      <p style="color:#7f1d1d;font-size:.84rem;margin:0 0 6px;line-height:1.6">{{ $verification->note_admin }}</p>
      <p style="color:#6b7280;font-size:.78rem;margin:0">Corrigez les points ci-dessus et soumettez à nouveau.</p>
    </div>
  @endif

  <form method="POST" action="{{ route('recruteur.verification.store') }}" enctype="multipart/form-data" style="width:100%;max-width:680px">
    @csrf

    {{-- ─── Section 1 : Pièce d'identité ─────────────────── --}}
    <div class="vf-card">
      <div class="vf-card__header">
        <div class="vf-card__num">1</div>
        <div class="vf-card__title">Pièce d'identité</div>
      </div>
      <p class="vf-card__desc">Fournissez l'une des deux pièces ci-dessous (pas les deux obligatoirement).</p>

      @error('carte_biometrique')
        <p class="vf-error" style="margin-bottom:12px">{{ $message }}</p>
      @enderror

      {{-- Carte biométrique --}}
      <label class="vf-field-label">
        Carte nationale biométrique
        <span style="font-weight:400;color:#94a3b8">(recto-verso de préférence)</span>
      </label>

      @if($verification?->carte_biometrique)
        <div class="vf-badge-existing">
          <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Document actuel conservé — sélectionnez un fichier pour le remplacer
        </div>
      @endif

      <div class="vf-upload" id="zoneCarteB">
        <input type="file" name="carte_biometrique" accept=".pdf,.jpg,.jpeg,.png"
               onchange="afficherFichier(this,'zoneCarteB')">
        <div class="vf-upload__icon">
          <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
          </svg>
        </div>
        <div class="vf-upload__text"><strong>Cliquez</strong> ou déposez votre fichier ici</div>
        <div class="vf-upload__hint">PDF, JPG ou PNG — max 5 Mo</div>
        <div class="vf-upload__selected" style="display:none;margin-top:8px;font-size:.8rem;color:#2563eb;font-weight:600"></div>
      </div>

      <div class="vf-ou"><span>OU</span></div>

      {{-- CIP --}}
      <label class="vf-field-label">
        CIP — Carte d'Identité Professionnelle
      </label>

      @if($verification?->cip)
        <div class="vf-badge-existing">
          <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Document actuel conservé — sélectionnez un fichier pour le remplacer
        </div>
      @endif

      <div class="vf-upload" id="zoneCip">
        <input type="file" name="cip" accept=".pdf,.jpg,.jpeg,.png"
               onchange="afficherFichier(this,'zoneCip')">
        <div class="vf-upload__icon">
          <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
          </svg>
        </div>
        <div class="vf-upload__text"><strong>Cliquez</strong> ou déposez votre fichier ici</div>
        <div class="vf-upload__hint">PDF, JPG ou PNG — max 5 Mo</div>
        <div class="vf-upload__selected" style="display:none;margin-top:8px;font-size:.8rem;color:#2563eb;font-weight:600"></div>
      </div>
    </div>

    {{-- ─── Section 2 : Justificatif d'entreprise ─────────── --}}
    <div class="vf-card">
      <div class="vf-card__header">
        <div class="vf-card__num">2</div>
        <div class="vf-card__title">Justificatif d'entreprise</div>
      </div>
      <p class="vf-card__desc">Fournissez votre IFU ou votre RCCM — numéro <strong>ou</strong> document, selon ce que vous avez sous la main.</p>

      @error('ifu_numero')
        <p class="vf-error" style="margin-bottom:12px">{{ $message }}</p>
      @enderror

      {{-- IFU --}}
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:18px;margin-bottom:0">
        <div style="font-weight:700;color:#374151;font-size:.88rem;margin-bottom:12px;display:flex;align-items:center;gap:8px">
          <span style="background:#eff6ff;color:#2563eb;padding:2px 8px;border-radius:4px;font-size:.75rem">IFU</span>
          Identifiant Fiscal Unique
        </div>

        <label class="vf-field-label">Numéro IFU (si vous le connaissez)</label>
        <input class="vf-input" type="text" name="ifu_numero"
               value="{{ old('ifu_numero', $verification?->ifu_numero) }}"
               placeholder="Ex : 123456789" maxlength="50">

        <div class="vf-ou" style="margin:12px 0"><span>OU</span></div>

        <label class="vf-field-label">Document IFU (PDF ou image)</label>
        @if($verification?->ifu_fichier)
          <div class="vf-badge-existing" style="margin-bottom:8px">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Document actuel conservé
          </div>
        @endif
        <div class="vf-upload" id="zoneIfu">
          <input type="file" name="ifu_fichier" accept=".pdf,.jpg,.jpeg,.png"
                 onchange="afficherFichier(this,'zoneIfu')">
          <div class="vf-upload__icon">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
          </div>
          <div class="vf-upload__text"><strong>Cliquez</strong> pour sélectionner</div>
          <div class="vf-upload__hint">PDF, JPG ou PNG — max 5 Mo</div>
          <div class="vf-upload__selected" style="display:none;margin-top:8px;font-size:.8rem;color:#2563eb;font-weight:600"></div>
        </div>
      </div>

      <div class="vf-ou"><span>OU</span></div>

      {{-- RCCM --}}
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:18px">
        <div style="font-weight:700;color:#374151;font-size:.88rem;margin-bottom:12px;display:flex;align-items:center;gap:8px">
          <span style="background:#f0fdf4;color:#059669;padding:2px 8px;border-radius:4px;font-size:.75rem">RCCM</span>
          Registre du Commerce et du Crédit Mobilier
        </div>

        <label class="vf-field-label">Numéro RCCM (si vous le connaissez)</label>
        <input class="vf-input" type="text" name="rccm_numero"
               value="{{ old('rccm_numero', $verification?->rccm_numero) }}"
               placeholder="Ex : RB/COT/2020/A/12345" maxlength="100">

        <div class="vf-ou" style="margin:12px 0"><span>OU</span></div>

        <label class="vf-field-label">Document RCCM (PDF ou image)</label>
        @if($verification?->rccm_fichier)
          <div class="vf-badge-existing" style="margin-bottom:8px">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Document actuel conservé
          </div>
        @endif
        <div class="vf-upload" id="zoneRccm">
          <input type="file" name="rccm_fichier" accept=".pdf,.jpg,.jpeg,.png"
                 onchange="afficherFichier(this,'zoneRccm')">
          <div class="vf-upload__icon">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
          </div>
          <div class="vf-upload__text"><strong>Cliquez</strong> pour sélectionner</div>
          <div class="vf-upload__hint">PDF, JPG ou PNG — max 5 Mo</div>
          <div class="vf-upload__selected" style="display:none;margin-top:8px;font-size:.8rem;color:#2563eb;font-weight:600"></div>
        </div>
      </div>
    </div>

    {{-- Bouton de soumission --}}
    <div class="vf-submit-wrap">
      <button type="submit" class="vf-submit">
        {{ $verification?->estRejete() ? 'Resoumettre le dossier corrigé' : 'Soumettre le dossier de vérification' }}
      </button>
      <form method="POST" action="{{ route('auth.deconnecter') }}" style="text-align:center;margin-top:14px">
        @csrf
        <button type="submit" style="background:none;border:none;color:#9ca3af;font-size:.8rem;cursor:pointer;text-decoration:underline">
          Se déconnecter
        </button>
      </form>
    </div>

  </form>
</div>
@endsection

@section('scripts')
<script>
function afficherFichier(input, zoneId) {
  const zone = document.getElementById(zoneId);
  const label = zone.querySelector('.vf-upload__selected');
  const icon  = zone.querySelector('.vf-upload__icon');
  const text  = zone.querySelector('.vf-upload__text');
  const hint  = zone.querySelector('.vf-upload__hint');

  if (input.files && input.files[0]) {
    const nom = input.files[0].name;
    const taille = (input.files[0].size / 1024 / 1024).toFixed(2);
    label.textContent = '✓ ' + nom + ' (' + taille + ' Mo)';
    label.style.display = 'block';
    icon.style.display = 'none';
    text.style.display = 'none';
    hint.style.display = 'none';
    zone.style.borderColor = '#2563eb';
    zone.style.background  = '#eff6ff';
  }
}
</script>
@endsection
