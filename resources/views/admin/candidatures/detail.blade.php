@extends('layouts.admin')
@section('title', 'Candidature | Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.candidatures.list') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour</a>
    <h1>{{ $candidature->offre->titre }}</h1>
    <p>{{ $candidature->candidat->nom_complet }} · {{ $candidature->candidat->email }}</p>
  </div>
  <div style="display:flex;gap:10px">
    <form method="POST" action="{{ route('admin.candidatures.destroy', $candidature) }}" data-confirm="Supprimer cette candidature ?" data-confirm-btn="Supprimer">
      @csrf @method('DELETE')
      <button type="submit" class="adm-btn adm-btn--danger">Supprimer</button>
    </form>
  </div>
</div>

<div class="adm-grid-2" style="align-items:start">
  <div>
    <div class="adm-card" style="margin-bottom:20px">
      <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Candidature</h3>
        <span class="adm-badge adm-badge--{{ match($candidature->statut) {
          'retenue'   => 'green',
          'refusee'   => 'red',
          'entretien' => 'violet',
          'vue'       => 'blue',
          default     => 'gray'
        } }}">{{ ucfirst(str_replace('_',' ',$candidature->statut)) }}</span>
      </div>
      <div style="padding:20px 24px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
          <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Type de contrat</p><p style="font-weight:600;color:#042C53;margin:0">{{ $candidature->offre?->type?->libelle }}</p></div>
          <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Date de candidature</p><p style="font-weight:600;color:#042C53;margin:0">{{ $candidature->created_at->format('d/m/Y') }}</p></div>
        </div>

        @if($candidature->message_motivation)
          <div>
            <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 8px">Message de motivation</p>
            <p style="font-size:14px;color:#374151;line-height:1.65;margin:0">{{ $candidature->message_motivation }}</p>
          </div>
        @endif

        @if($candidature->note_recruteur)
          <div style="margin-top:16px;padding:14px 18px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0">
            <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#64748b;margin:0 0 6px">Note du recruteur</p>
            <p style="font-size:13.5px;color:#374151;margin:0">{{ $candidature->note_recruteur }}</p>
          </div>
        @endif

        @php
          $cvAffiche = $candidature->cv_snapshot ?: ($candidature->cv ? [
              'metier'       => $candidature->cv->metier,
              'ville'        => $candidature->cv->ville,
              'fichier_path' => $candidature->cv->fichier_path,
          ] : null);
        @endphp
        <div style="margin-top:20px;padding-top:20px;border-top:1px solid #e2e8f0">
          <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#64748b;margin:0 0 10px">CV joint</p>
          @if($cvAffiche)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#f0f7ff;border:1.5px solid #bfdbfe;border-radius:10px">
              <div style="flex:1;min-width:0">
                <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:14px">{{ $cvAffiche['metier'] }}</p>
                <p style="font-size:12px;color:#64748b;margin:0">{{ $cvAffiche['ville'] }}</p>
              </div>
              @if($cvAffiche['fichier_path'])
              <a href="{{ Storage::url($cvAffiche['fichier_path']) }}" target="_blank" class="adm-btn adm-btn--outline adm-btn--sm">Télécharger</a>
              @endif
            </div>
          @elseif($candidature->cv_path)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px">
              <div style="flex:1;min-width:0">
                <p style="font-weight:600;color:#042C53;margin:0 0 2px;font-size:14px">Fichier CV joint</p>
                <p style="font-size:12px;color:#64748b;margin:0">{{ basename($candidature->cv_path) }}</p>
              </div>
              <a href="{{ Storage::url($candidature->cv_path) }}" target="_blank" class="adm-btn adm-btn--outline adm-btn--sm">Télécharger</a>
            </div>
          @else
            <p style="font-size:13.5px;color:#64748b;font-style:italic;margin:0">Aucun CV joint à cette candidature.</p>
          @endif
        </div>

        @if($candidature->documents->isNotEmpty())
        <div style="margin-top:20px;padding-top:20px;border-top:1px solid #e2e8f0">
          <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#64748b;margin:0 0 10px">Pièces justificatives jointes</p>
          <div style="display:flex;flex-direction:column;gap:8px">
            @foreach($candidature->documents as $doc)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px">
              <div style="flex:1;min-width:0">
                <p style="font-weight:600;color:#042C53;margin:0 0 2px;font-size:14px">{{ $doc->nom }}</p>
                <p style="font-size:12px;color:#64748b;margin:0">{{ $doc->type?->nom }}</p>
              </div>
              <a href="{{ Storage::url($doc->fichier) }}" target="_blank" class="adm-btn adm-btn--outline adm-btn--sm">Voir</a>
            </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <div style="position:sticky;top:24px">
    <div class="adm-card" style="margin-bottom:16px">
      <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Candidat</h3>
      </div>
      <div style="padding:20px 24px">
        <p style="font-weight:700;color:#042C53;margin:0 0 4px">{{ $candidature->candidat->nom_complet }}</p>
        <p style="font-size:13px;color:#64748b;margin:0">{{ $candidature->candidat->email }}</p>
      </div>
    </div>

    <div class="adm-card">
      <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Offre</h3>
      </div>
      <div style="padding:20px 24px">
        <p style="font-weight:700;color:#042C53;margin:0 0 4px">{{ $candidature->offre->titre }}</p>
        <p style="font-size:13px;color:#64748b;margin:0 0 12px">{{ $candidature->offre->entreprise }}</p>
        <a href="{{ route('admin.offres.detail', $candidature->offre) }}" class="adm-btn adm-btn--outline" style="width:100%;justify-content:center">Voir l'offre (admin)</a>
      </div>
    </div>
  </div>
</div>
@endsection
