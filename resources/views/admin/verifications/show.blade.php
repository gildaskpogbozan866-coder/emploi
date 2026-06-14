@extends('layouts.admin')
@section('title', 'Dossier — ' . $verification->user->prenom . ' ' . $verification->user->nom)

@section('content')
<div class="dash-content">

  <div class="dash-content__header">
    <div style="display:flex;align-items:center;gap:12px">
      <a href="{{ route('admin.verifications.list') }}" style="color:#6b7280;text-decoration:none;font-size:.88rem">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour à la liste
      </a>
    </div>
    <h1 class="dash-content__title" style="margin-top:8px">
      Dossier de {{ $verification->user->prenom }} {{ $verification->user->nom }}
    </h1>
    <p class="dash-content__sub">{{ $verification->user->entreprise }} — {{ $verification->user->email }}</p>
  </div>

  @if(session('success'))
    <div style="background:#dcfce7;border:1.5px solid #bbf7d0;border-radius:10px;padding:12px 18px;margin-bottom:20px;font-size:13.5px;color:#15803d">
      {{ session('success') }}
    </div>
  @endif

  <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start">

    {{-- Colonne principale : documents --}}
    <div>
      <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:24px;margin-bottom:20px">
        <h2 style="font-size:1rem;font-weight:700;color:#111827;margin:0 0 20px">Documents soumis</h2>

        @forelse($documents as $doc)
          <div style="margin-bottom:28px;padding-bottom:24px;border-bottom:1px solid #f1f5f9;last:border-0">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
              <p style="font-weight:700;color:#374151;font-size:.9rem;margin:0">{{ $doc->type->nom }}</p>
              @if($doc->type->est_requis)
                <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#fef9c3;color:#ca8a04;font-weight:600">Requis</span>
              @else
                <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#f1f5f9;color:#94a3b8;font-weight:600">Optionnel</span>
              @endif
            </div>

            @if($doc->texte)
              <div style="background:#f8fafc;border-radius:8px;padding:10px 14px;margin-bottom:{{ $doc->fichier ? '12px' : '0' }}">
                <span style="font-size:.83rem;color:#374151;font-weight:600">Référence / Numéro : </span>
                <code style="background:#e2e8f0;padding:2px 8px;border-radius:4px;font-size:.88rem;margin-left:4px">{{ $doc->texte }}</code>
              </div>
            @endif

            @if($doc->fichier)
              @if($doc->estImage())
                <img src="{{ route('admin.verifications.document', $doc) }}"
                     alt="{{ $doc->type->nom }}"
                     style="max-width:100%;border-radius:8px;border:1px solid #e5e7eb;display:block" />
              @else
                <iframe src="{{ route('admin.verifications.document', $doc) }}"
                        width="100%" height="520"
                        style="border:1px solid #e5e7eb;border-radius:8px;display:block"
                        title="{{ $doc->type->nom }}"></iframe>
              @endif
              <a href="{{ route('admin.verifications.document', $doc) }}"
                 target="_blank"
                 style="display:inline-block;margin-top:8px;font-size:.8rem;color:#2563eb;text-decoration:none">
                Ouvrir dans un nouvel onglet →
              </a>
            @endif
          </div>
        @empty
          <p style="font-size:13.5px;color:#94a3b8;margin:0">Aucun document soumis.</p>
        @endforelse
      </div>
    </div>

    {{-- Colonne droite : décision --}}
    <div>
      {{-- Infos recruteur --}}
      <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:20px;margin-bottom:16px">
        <h3 style="font-size:.9rem;font-weight:700;color:#111827;margin:0 0 14px">Informations</h3>
        <div style="font-size:.83rem;color:#374151;line-height:1.9">
          <div><strong>Nom :</strong> {{ $verification->user->prenom }} {{ $verification->user->nom }}</div>
          <div><strong>E-mail :</strong> {{ $verification->user->email }}</div>
          <div><strong>Tél :</strong> {{ $verification->user->telephone ?? '—' }}</div>
          <div><strong>Entreprise :</strong> {{ $verification->user->entreprise ?? '—' }}</div>
          <div><strong>Soumis le :</strong> {{ $verification->created_at->format('d/m/Y à H:i') }}</div>
          @if($verification->reviewed_at)
            <div><strong>Examiné le :</strong> {{ $verification->reviewed_at->format('d/m/Y à H:i') }}</div>
            <div><strong>Par :</strong> {{ $verification->reviewedBy?->prenom }} {{ $verification->reviewedBy?->nom }}</div>
          @endif
        </div>
      </div>

      {{-- Statut actuel --}}
      <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:20px;margin-bottom:16px;text-align:center">
        @if($verification->statut === 'en_attente')
          <span style="background:#fef3c7;color:#d97706;padding:6px 18px;border-radius:20px;font-size:.88rem;font-weight:700">En attente de décision</span>
        @elseif($verification->statut === 'approuve')
          <span style="background:#d1fae5;color:#059669;padding:6px 18px;border-radius:20px;font-size:.88rem;font-weight:700">Approuvé</span>
        @else
          <span style="background:#fef2f2;color:#dc2626;padding:6px 18px;border-radius:20px;font-size:.88rem;font-weight:700">Rejeté</span>
          @if($verification->note_admin)
            <p style="font-size:.82rem;color:#7f1d1d;margin:10px 0 0;text-align:left">{{ $verification->note_admin }}</p>
          @endif
        @endif
      </div>

      {{-- Bouton Approuver --}}
      @if($verification->statut !== 'approuve')
        <form method="POST" action="{{ route('admin.verifications.approuver', $verification) }}" style="margin-bottom:12px">
          @csrf @method('PATCH')
          <button type="submit" class="adm-btn adm-btn--green" style="width:100%;justify-content:center">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Approuver le compte
          </button>
        </form>
      @endif

      {{-- Formulaire de rejet --}}
      @if($verification->statut !== 'rejete')
        <div class="adm-card" style="border-color:#fca5a5;padding:20px">
          <h3 style="font-size:.88rem;font-weight:700;color:#dc2626;margin:0 0 12px">Rejeter le dossier</h3>
          <form method="POST" action="{{ route('admin.verifications.rejeter', $verification) }}">
            @csrf @method('PATCH')
            <div class="adm-form-field" style="margin-bottom:12px">
              <label class="adm-form-label">
                Motif du rejet <span style="color:#dc2626">*</span>
              </label>
              <textarea name="note_admin" rows="4" class="adm-form-input"
                        placeholder="Expliquez clairement ce qui manque ou ce qui doit être corrigé…"
                        required>{{ old('note_admin') }}</textarea>
              @error('note_admin')
                <p style="color:#dc2626;font-size:.8rem;margin:4px 0 0">{{ $message }}</p>
              @enderror
            </div>
            <button type="submit" class="adm-btn adm-btn--danger" style="width:100%;justify-content:center">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              Rejeter le dossier
            </button>
          </form>
        </div>
      @endif

    </div>
  </div>

</div>
@endsection
