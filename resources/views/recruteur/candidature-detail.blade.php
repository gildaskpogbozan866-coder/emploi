@extends('layouts.recruteur')
@section('title', 'Candidature — Recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <a href="{{ route('recruteur.candidatures') }}" class="rec-btn rec-btn--outline rec-btn--sm" style="margin-bottom:8px"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour</a>
    <h1>Candidature de {{ $candidature->candidat->nom_complet }}</h1>
    <p>Pour : {{ $candidature->offre->titre }}</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
  <div>
    <div class="rec-card" style="margin-bottom:20px">
      <div class="rec-card__head">
        <span class="rec-card__title">Message de motivation</span>
        <span class="rec-badge rec-badge--{{ match($candidature->statut) {
          'retenue'   => 'green',
          'refusee'   => 'red',
          'entretien' => 'green',
          'vue'       => 'blue',
          default     => 'gray'
        } }}">{{ ucfirst(str_replace('_',' ',$candidature->statut)) }}</span>
      </div>
      <div class="rec-card__body">
        @if($candidature->message_motivation)
          <p style="font-size:14.5px;color:#374151;line-height:1.7;margin:0">{{ $candidature->message_motivation }}</p>
        @else
          <p style="color:#94a3b8;font-style:italic;margin:0">Aucun message de motivation fourni.</p>
        @endif
      </div>
    </div>

    <div class="rec-card" style="margin-bottom:20px">
      <div class="rec-card__head">
        <span class="rec-card__title">CV joint à la candidature</span>
      </div>
      <div class="rec-card__body">
        @if($candidature->cv)
          <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#f0f7ff;border:1.5px solid #bfdbfe;border-radius:10px">
            <div style="width:40px;height:40px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0">
              <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div style="flex:1;min-width:0">
              <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:14px">{{ $candidature->cv->titre_poste }}</p>
              <p style="font-size:12.5px;color:#64748b;margin:0 0 6px">{{ $candidature->cv->pays }}{{ $candidature->cv->ville ? ' · '.$candidature->cv->ville : '' }}</p>
              @if($candidature->cv->competences)
                <p style="font-size:12.5px;color:#374151;margin:0">{{ Str::limit($candidature->cv->competences, 120) }}</p>
              @endif
            </div>
            <span style="font-size:11px;background:#dbeafe;color:#1e40af;padding:2px 10px;border-radius:20px;font-weight:600;flex-shrink:0">Profil</span>
          </div>
        @elseif($candidature->cv_path)
          <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px">
            <div style="width:40px;height:40px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0">
              <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            </div>
            <div style="flex:1;min-width:0">
              <p style="font-weight:600;color:#042C53;margin:0 0 2px;font-size:14px">Fichier CV joint</p>
              <p style="font-size:12px;color:#64748b;margin:0">{{ basename($candidature->cv_path) }}</p>
            </div>
            <a href="{{ asset('storage/' . $candidature->cv_path) }}" target="_blank"
               style="font-size:13px;font-weight:700;color:#185FA5;text-decoration:none;white-space:nowrap;flex-shrink:0;padding:7px 14px;border:1.5px solid #bfdbfe;border-radius:7px;background:#f0f7ff">
              Télécharger
            </a>
          </div>
        @else
          <p style="font-size:13.5px;color:#64748b;font-style:italic;margin:0">Le candidat n'a pas joint de CV à cette candidature.</p>
        @endif
      </div>
    </div>

    <div class="rec-card">
      <div class="rec-card__head">
        <span class="rec-card__title">Mettre à jour le statut</span>
      </div>
      <div class="rec-card__body">
        <form method="POST" action="{{ route('recruteur.candidatures.statut', $candidature) }}" style="display:flex;flex-direction:column;gap:14px">
          @csrf @method('PATCH')
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Nouveau statut</label>
            <select name="statut" class="rec-select" style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px">
              @foreach(['envoyee' => 'Envoyée','vue' => 'Vue','retenue' => 'Retenue (passé en entretien)','entretien' => 'Entretien planifié','refusee' => 'Refusée'] as $val => $label)
                <option value="{{ $val }}" {{ $candidature->statut === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Note interne (visible par le candidat)</label>
            <textarea name="note_recruteur" rows="3" placeholder="Commentaire envoyé au candidat avec sa notification…"
                      style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ $candidature->note_recruteur }}</textarea>
          </div>
          <button type="submit" class="rec-btn rec-btn--primary">Enregistrer et notifier le candidat</button>
        </form>
      </div>
    </div>
  </div>

  <div>
    <div class="rec-card">
      <div class="rec-card__head">
        <span class="rec-card__title">Candidat</span>
      </div>
      <div class="rec-card__body" style="display:flex;flex-direction:column;gap:10px">
        <div style="width:56px;height:56px;border-radius:50%;background:rgba(55,138,221,0.12);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.2rem;color:#185FA5;margin-bottom:4px">
          {{ strtoupper(substr($candidature->candidat->prenom ?? '?', 0, 1)) }}
        </div>
        <div>
          <p style="font-weight:700;color:#042C53;margin:0 0 2px">{{ $candidature->candidat->nom_complet }}</p>
          <p style="font-size:12.5px;color:#94a3b8;margin:0">{{ $candidature->candidat->pays ?? '—' }}</p>
        </div>
        <a href="{{ route('recruteur.messagerie') }}" class="rec-btn rec-btn--outline" style="width:100%;justify-content:center">
          Envoyer un message
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
