@extends('layouts.app')
@section('title', 'Commander — ' . $service->nom)

@section('content')
<section style="padding:48px 20px 64px;background:#f8fafc;min-height:70vh">
  <div style="max-width:760px;margin:0 auto">

    <a href="{{ route('service.detail', $service) }}"
       style="display:inline-flex;align-items:center;gap:6px;color:#185FA5;font-size:13.5px;margin-bottom:28px;text-decoration:none;font-weight:500">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Retour au service
    </a>

    {{-- Résumé service --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px 24px;margin-bottom:20px;display:flex;gap:16px;align-items:center;flex-wrap:wrap">
      <div style="width:52px;height:52px;border-radius:12px;background:#fef9c3;display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#92400e" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        </svg>
      </div>
      <div style="flex:1">
        <p style="font-weight:800;color:#042C53;font-size:1rem;margin:0 0 4px">{{ $service->nom }}</p>
        <p style="color:#64748b;font-size:13px;margin:0;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
          <strong style="color:#185FA5;font-size:1rem">{{ number_format($service->prix, 0, ',', ' ') }} FCFA</strong>
          @if($service->delai)
            <span style="color:#cbd5e0">·</span>
            <span>Livré sous {{ $service->delai }}</span>
          @endif
        </p>
      </div>
    </div>

    {{-- Formulaire --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:36px">
      <h1 style="font-size:1.4rem;font-weight:800;color:#042C53;margin:0 0 6px">Passer commande</h1>
      <p style="color:#64748b;font-size:13.5px;margin:0 0 28px;line-height:1.55">
        Décrivez votre demande en détail afin que notre équipe puisse vous livrer un résultat optimal.
      </p>

      @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:22px">
          <p style="font-weight:700;color:#dc2626;margin:0 0 6px;font-size:13.5px">Veuillez corriger les erreurs :</p>
          <ul style="margin:0;padding-left:18px;color:#dc2626;font-size:13px">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('service.commande.store', $service) }}" enctype="multipart/form-data">
        @csrf

        {{-- Détails de la demande --}}
        <div style="margin-bottom:24px">
          <label style="display:block;font-size:13.5px;font-weight:700;color:#374151;margin-bottom:8px">
            Détaillez votre demande <span style="color:#e53e3e">*</span>
          </label>
          <textarea name="details_demande" rows="7" required
                    placeholder="Décrivez votre situation, vos objectifs, le poste visé, vos attentes spécifiques…"
                    style="width:100%;padding:14px 16px;border:1.5px solid {{ $errors->has('details_demande') ? '#e53e3e' : '#d1d5db' }};border-radius:10px;font-size:14px;font-family:inherit;color:#1e293b;resize:vertical;box-sizing:border-box;line-height:1.65;outline:none"
                    onfocus="this.style.borderColor='#185FA5';this.style.boxShadow='0 0 0 3px rgba(24,95,165,.1)'"
                    onblur="this.style.borderColor='#d1d5db';this.style.boxShadow='none'">{{ old('details_demande') }}</textarea>
          @error('details_demande')
            <p style="color:#dc2626;font-size:12.5px;margin:6px 0 0">{{ $message }}</p>
          @enderror
          <p style="font-size:12px;color:#94a3b8;margin:5px 0 0">Minimum 20 caractères</p>
        </div>

        {{-- Fichier joint --}}
        <div style="margin-bottom:24px">
          <label style="display:block;font-size:13.5px;font-weight:700;color:#374151;margin-bottom:8px">
            Joindre un document
            <span style="font-weight:400;color:#94a3b8;font-size:12px">— optionnel (CV actuel, notes…)</span>
          </label>
          <label style="display:flex;align-items:center;gap:14px;padding:18px 20px;border:2px dashed #cbd5e0;border-radius:12px;cursor:pointer;background:#fafafa"
                 onmouseover="this.style.borderColor='#185FA5';this.style.background='#f0f7ff'"
                 onmouseout="this.style.borderColor='#cbd5e0';this.style.background='#fafafa'">
            <div style="width:42px;height:42px;border-radius:10px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0">
              <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
              </svg>
            </div>
            <div style="flex:1">
              <p id="commFileLabel" style="font-size:14px;font-weight:600;color:#185FA5;margin:0">Cliquer pour joindre un fichier</p>
              <p style="font-size:12px;color:#94a3b8;margin:3px 0 0">PDF, DOC, DOCX, TXT — 10 Mo maximum</p>
            </div>
            <input type="file" name="fichier_joint" accept=".pdf,.doc,.docx,.txt" style="display:none"
                   onchange="document.getElementById('commFileLabel').textContent = this.files[0] ? this.files[0].name : 'Cliquer pour joindre un fichier'">
          </label>
        </div>

        {{-- Email si non connecté --}}
        @guest
        <div style="margin-bottom:24px">
          <label style="display:block;font-size:13.5px;font-weight:700;color:#374151;margin-bottom:8px">
            Votre email pour le suivi <span style="color:#e53e3e">*</span>
          </label>
          <input type="email" name="email_contact" required placeholder="vous@exemple.com"
                 value="{{ old('email_contact') }}"
                 style="width:100%;padding:13px 16px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px;font-family:inherit;color:#1e293b;box-sizing:border-box;outline:none"
                 onfocus="this.style.borderColor='#185FA5'" onblur="this.style.borderColor='#d1d5db'">
        </div>
        @endguest

        {{-- Info paiement --}}
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;margin-bottom:26px;display:flex;gap:10px;align-items:flex-start">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#92400e" stroke-width="2" style="flex-shrink:0;margin-top:2px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p style="font-size:13px;color:#92400e;margin:0;line-height:1.6">
            <strong>Paiement après validation.</strong> Notre équipe vous contacte sous 24h pour confirmer votre commande et organiser le paiement (Mobile Money ou virement).
          </p>
        </div>

        {{-- Boutons --}}
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <button type="submit"
                  style="flex:1;min-width:220px;padding:15px 24px;background:#F5C842;color:#042C53;border:none;border-radius:10px;font-weight:800;font-size:15px;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px"
                  onmouseover="this.style.background='#e0a800'" onmouseout="this.style.background='#F5C842'">
            Envoyer ma commande →
          </button>
          <a href="{{ route('service.list') }}"
             style="padding:15px 24px;background:#f1f5f9;color:#374151;border-radius:10px;font-weight:600;font-size:15px;text-decoration:none;display:inline-flex;align-items:center">
            Annuler
          </a>
        </div>
      </form>
    </div>

  </div>
</section>
@endsection
