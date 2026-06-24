@extends('layouts.recruteur')
@section('title', 'Candidatures reçues')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Candidatures reçues</h1>
    <p>Gérez et suivez toutes les candidatures à vos offres</p>
  </div>
</div>

{{-- Filtres --}}
<div class="rec-card" style="margin-bottom:18px">
  <div class="rec-card__body" style="padding:16px 22px">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
      <div style="display:flex;flex-direction:column;gap:5px;flex:2;min-width:180px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Rechercher</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, prénom ou email…"
               style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13.5px;color:#042C53;background:#fff;outline:none">
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;flex:2;min-width:180px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Offre</label>
        <select name="offre_id" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Toutes les offres</option>
          @foreach($offres as $id => $titre)
            <option value="{{ $id }}" {{ request('offre_id') == $id ? 'selected' : '' }}>{{ $titre }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:150px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Statut</label>
        <select name="statut" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="">Tous les statuts</option>
          @foreach(\App\Enums\StatutCandidature::cases() as $case)
            <option value="{{ $case->value }}" {{ request('statut') === $case->value ? 'selected' : '' }}>{{ $case->label() }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;flex-direction:column;gap:5px;min-width:150px">
        <label style="font-size:11.5px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em">Trier par</label>
        <select name="tri" style="padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13.5px;color:#042C53;background:#fff;outline:none">
          <option value="recent"  {{ request('tri','recent') === 'recent'  ? 'selected' : '' }}>Plus récentes</option>
          <option value="ancien"  {{ request('tri') === 'ancien'  ? 'selected' : '' }}>Plus anciennes</option>
          <option value="statut"  {{ request('tri') === 'statut'  ? 'selected' : '' }}>Statut</option>
          <option value="nom"     {{ request('tri') === 'nom'     ? 'selected' : '' }}>Nom candidat</option>
        </select>
      </div>
      <button type="submit" class="rec-btn rec-btn--primary">Filtrer</button>
      @if(request()->hasAny(['offre_id','statut','q','tri']))
        <a href="{{ route('recruteur.candidatures') }}" class="rec-btn rec-btn--outline">Réinitialiser</a>
      @endif
      <a href="{{ route('recruteur.candidatures.export', request()->only(['offre_id','statut','q','tri'])) }}"
         class="rec-btn rec-btn--outline" style="white-space:nowrap">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px;margin-right:4px"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Exporter CSV
      </a>
    </form>
  </div>
</div>

<div class="rec-card">
  <div class="rec-table-wrap">
    <table class="rec-table">
      <thead>
        <tr><th>Candidat</th><th>Poste</th><th>CV</th><th>Statut</th><th>Date</th><th>Action</th></tr>
      </thead>
      <tbody>
        @forelse($candidatures as $c)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div class="rec-cand-row__avatar" style="width:36px;height:36px;font-size:13px">
                {{ strtoupper(substr($c->candidat->prenom ?? '?', 0, 1)) }}
              </div>
              <div>
                <div style="font-weight:600;color:#042C53;font-size:13.5px">{{ $c->candidat->nom_complet }}</div>
                <div style="font-size:11.5px;color:#94a3b8">{{ $c->candidat->email }}</div>
              </div>
            </div>
          </td>
          <td style="color:#6b7a8d;font-size:13px">{{ $c->offre->titre }}</td>
          <td style="text-align:center">
            @if($c->cv_id)
              <span title="CV joint" style="color:#16a34a">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              </span>
            @else
              <span title="Pas de CV" style="color:#cbd5e1">–</span>
            @endif
          </td>
          <td>
            @php $statut = \App\Enums\StatutCandidature::tryFrom($c->statut) @endphp
            <span class="rec-badge rec-badge--{{ $statut?->couleur() ?? 'gray' }}">
              {{ $statut?->label() ?? ucfirst($c->statut) }}
            </span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $c->created_at->format('d/m/Y') }}</td>
          <td>
            <a href="{{ route('recruteur.candidatures.show', $c) }}" class="rec-btn rec-btn--outline rec-btn--sm">Consulter <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6">
            <div class="rec-empty">
              <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
              <h3>Aucune candidature reçue</h3>
              <p>Les candidatures à vos offres apparaîtront ici.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($candidatures->hasPages())
    <div style="padding:16px 22px">{{ $candidatures->links() }}</div>
  @endif
</div>
@endsection
