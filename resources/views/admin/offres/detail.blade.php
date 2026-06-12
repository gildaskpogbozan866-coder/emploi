@extends('layouts.admin')
@section('title', 'Offre — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.offres.list') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour</a>
    <h1>{{ $offre->titre }}</h1>
    <p>{{ $offre->entreprise }} · {{ $offre->localisation }}</p>
  </div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('offre.detail', $offre) }}" target="_blank" class="adm-btn adm-btn--outline">Voir public</a>
    <form method="POST" action="{{ route('admin.offres.destroy', $offre) }}" data-confirm="Supprimer cette offre ?" data-confirm-btn="Supprimer">
      @csrf @method('DELETE')
      <button type="submit" class="adm-btn adm-btn--danger">Supprimer</button>
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start">
  <div>
    <div class="adm-card" style="margin-bottom:20px">
      <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Détails de l'offre</h3>
      </div>
      <div style="padding:20px 24px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
          <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Type de contrat</p><p style="font-weight:600;color:#042C53;margin:0">{{ $offre->type }}</p></div>
          <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Secteur</p><p style="font-weight:600;color:#042C53;margin:0">{{ $offre->secteur ? (is_array($offre->secteur) ? implode(', ', $offre->secteur) : $offre->secteur) : '—' }}</p></div>
          <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Localisation</p><p style="font-weight:600;color:#042C53;margin:0">{{ $offre->localisation }}</p></div>
          <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Candidatures</p><p style="font-size:1.2rem;font-weight:800;color:#185FA5;margin:0">{{ $offre->candidatures_count }}</p></div>
        </div>
        @if($offre->fichier)
          <div style="margin-bottom:16px">
            <a href="{{ Storage::url($offre->fichier) }}" target="_blank" rel="noopener"
               style="display:inline-flex;align-items:center;gap:7px;padding:8px 14px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:7px;color:#0284c7;font-size:13px;font-weight:600;text-decoration:none">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              Document joint
            </a>
          </div>
        @endif
        @if($offre->description)
          <div>
            <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 8px">Description</p>
            @php $descIsHtml = str_starts_with(ltrim($offre->description), '<'); @endphp
            <div style="font-size:14px;color:#374151;line-height:1.7">{!! $descIsHtml ? $offre->description : nl2br(e($offre->description)) !!}</div>
          </div>
        @endif
        @if($offre->competences->isNotEmpty())
          <div style="margin-top:16px">
            <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 8px">Compétences requises</p>
            <div style="display:flex;flex-wrap:wrap;gap:6px">
              @foreach($offre->competences as $comp)
                <span style="background:#dbeafe;color:#1e40af;font-size:12px;font-weight:600;padding:3px 10px;border-radius:20px">{{ $comp->nom }}</span>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>

    <div class="adm-card">
      <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Candidatures reçues ({{ $offre->candidatures->count() }})</h3>
      </div>
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>Candidat</th><th>Email</th><th>Statut</th><th>Date</th></tr></thead>
          <tbody>
            @forelse($offre->candidatures as $candidature)
            <tr>
              <td style="font-weight:600;color:#042C53">{{ $candidature->candidat->nom_complet }}</td>
              <td style="color:#64748b;font-size:12.5px">{{ $candidature->candidat->email }}</td>
              <td>
                <span class="adm-badge adm-badge--{{ match($candidature->statut) {
                  'retenue'   => 'green',
                  'refusee'   => 'red',
                  'entretien' => 'violet',
                  'vue'       => 'blue',
                  default     => 'gray'
                } }}">{{ ucfirst(str_replace('_',' ',$candidature->statut)) }}</span>
              </td>
              <td style="color:#94a3b8;font-size:12px">{{ $candidature->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:20px">Aucune candidature.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div style="position:sticky;top:24px">
    <div class="adm-card" style="margin-bottom:16px">
      <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Recruteur</h3>
      </div>
      <div style="padding:20px 24px">
        <p style="font-weight:700;color:#042C53;margin:0 0 4px">{{ $offre->recruteur->nom_complet }}</p>
        <p style="font-size:13px;color:#64748b;margin:0">{{ $offre->recruteur->entreprise ?? '—' }}</p>
      </div>
    </div>

    <div class="adm-card">
      <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
        <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Modifier le statut</h3>
      </div>
      <div style="padding:20px 24px">
        <form method="POST" action="{{ route('admin.offres.statut', $offre) }}">
          @csrf @method('PATCH')
          <select name="statut" class="adm-select" style="width:100%;margin-bottom:12px" onchange="this.form.submit()">
            @foreach(['en_attente' => 'En attente','active' => 'Active','expiree' => 'Expirée','suspendue' => 'Suspendue'] as $val => $label)
              <option value="{{ $val }}" {{ $offre->statut === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </form>
        <p style="font-size:12px;color:#94a3b8;margin:0">Publiée le {{ $offre->created_at->format('d/m/Y') }}</p>
      </div>
    </div>
  </div>
</div>
@endsection
