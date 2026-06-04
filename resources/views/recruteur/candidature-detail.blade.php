@extends('layouts.recruteur')
@section('title', 'Candidature — Recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <a href="{{ route('recruteur.candidatures') }}" class="rec-btn rec-btn--outline rec-btn--sm" style="margin-bottom:8px">← Retour</a>
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

    @if($candidature->candidat->cvs->isNotEmpty())
    <div class="rec-card" style="margin-bottom:20px">
      <div class="rec-card__head">
        <span class="rec-card__title">CV du candidat</span>
      </div>
      <div class="rec-card__body">
        @foreach($candidature->candidat->cvs as $cv)
          <div style="padding:12px;background:#f8fafc;border-radius:10px;margin-bottom:10px">
            <p style="font-weight:600;color:#042C53;margin:0 0 4px">{{ $cv->titre_poste }}</p>
            <p style="font-size:12.5px;color:#64748b;margin:0 0 8px">{{ $cv->pays }}{{ $cv->ville ? ' · '.$cv->ville : '' }}</p>
            @if($cv->competences)
              <p style="font-size:12.5px;color:#374151;margin:0">{{ Str::limit($cv->competences, 100) }}</p>
            @endif
          </div>
        @endforeach
      </div>
    </div>
    @endif

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
