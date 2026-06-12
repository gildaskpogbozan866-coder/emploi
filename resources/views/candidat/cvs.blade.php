@extends('layouts.candidat')
@section('title', 'CVs & Documents')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')

@if(session('success'))
  <div class="cand-alert cand-alert--success" style="margin-bottom:16px">{{ session('success') }}</div>
@endif

@if($total >= 1 && !$estPremium)
  <div style="display:flex;align-items:center;gap:12px;background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;padding:12px 16px;margin-bottom:20px">
    <svg width="18" height="18" fill="#F5C842" viewBox="0 0 24 24" style="flex-shrink:0"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
    <p style="margin:0;font-size:13px;color:#92400e;flex:1">Plan gratuit — <strong>1 document maximum</strong>. Passez au Premium pour ajouter autant de CVs, diplômes et attestations que vous voulez.</p>
    <a href="{{ route('candidat.abonnement') }}" class="cand-btn cand-btn--yellow cand-btn--sm" style="flex-shrink:0">Passer au Premium</a>
  </div>
@endif

{{-- En-tête --}}
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">CVs & Documents</h1>
    <p class="cand-page-header__sub">Gérez vos CVs, diplômes, attestations et certificats</p>
  </div>
  <div class="cand-page-header__actions">
    @if($total >= 1 && !$estPremium)
      <a href="{{ route('candidat.abonnement') }}" class="cand-btn cand-btn--yellow">
        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        Passer au Premium
      </a>
    @else
      <a href="{{ route('cv.public.depot') }}" class="cand-btn cand-btn--yellow">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Ajouter
      </a>
    @endif
  </div>
</div>

@php
  $total = $cvs->count() + $documents->count();
@endphp

@if($total === 0)
  <div class="cand-card">
    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      </div>
      <p class="cand-empty__title">Aucun document pour l'instant</p>
      <p class="cand-empty__text">Ajoutez votre CV, vos diplômes, attestations ou certificats.</p>
      <a href="{{ route('cv.public.depot') }}" class="cand-btn cand-btn--primary">Ajouter mon premier document</a>
    </div>
  </div>
@else

  {{-- Filtres --}}
  <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px" id="filters">
    <button class="doc-filter doc-filter--active" data-filter="tous">
      Tous <span class="doc-filter__count">{{ $total }}</span>
    </button>
    @if($cvs->isNotEmpty())
    <button class="doc-filter" data-filter="cv">
      Curriculum Vitae <span class="doc-filter__count">{{ $cvs->count() }}</span>
    </button>
    @endif
    @foreach($documents->groupBy('type.nom') as $typeName => $docs)
    <button class="doc-filter" data-filter="type-{{ Str::slug($typeName) }}">
      {{ $typeName }} <span class="doc-filter__count">{{ $docs->count() }}</span>
    </button>
    @endforeach
  </div>

  {{-- Liste unifiée --}}
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:14px" id="doc-grid">

    {{-- CVs --}}
    @foreach($cvs as $cv)
    <div class="cand-card doc-item" data-type="cv" style="margin-bottom:0">
      <div style="display:flex;align-items:flex-start;gap:12px">
        <div style="width:42px;height:42px;border-radius:8px;background:rgba(55,138,221,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div style="flex:1;min-width:0">
          <p style="font-weight:700;color:#042C53;margin:0;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $cv->titre_poste }}</p>
          <p style="font-size:12px;color:#64748b;margin:2px 0 4px">{{ $cv->pays }}{{ $cv->ville ? ', '.$cv->ville : '' }}</p>
          <span style="display:inline-block;font-size:11px;background:#EFF6FF;color:#1D4ED8;border-radius:4px;padding:1px 7px;font-weight:600">Curriculum Vitae</span>
        </div>
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:14px">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
          <span style="font-size:11px;color:#94a3b8">{{ $cv->created_at->format('d/m/Y') }} · {{ $cv->vues }} vue{{ $cv->vues > 1 ? 's' : '' }}</span>
          <form method="POST" action="{{ route('candidat.cvs.visibilite', $cv) }}" style="margin:0">
            @csrf @method('PATCH')
            <button type="submit"
                    title="{{ $cv->visible ? 'Masquer de la CVthèque' : 'Rendre visible dans la CVthèque' }}"
                    style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:2px 8px;border-radius:12px;border:1px solid {{ $cv->visible ? '#bbf7d0' : '#e2e8f0' }};background:{{ $cv->visible ? '#f0fdf4' : '#f8fafc' }};color:{{ $cv->visible ? '#16a34a' : '#94a3b8' }};cursor:pointer;line-height:1.6">
              <span style="width:6px;height:6px;border-radius:50%;background:{{ $cv->visible ? '#16a34a' : '#94a3b8' }};flex-shrink:0"></span>
              {{ $cv->visible ? 'Visible CVthèque' : 'Masqué' }}
            </button>
          </form>
        </div>
        <div style="display:flex;gap:6px">
          <a href="{{ route('candidat.cvs.edit', $cv) }}" class="cand-btn cand-btn--outline cand-btn--sm">Modifier</a>
          <form method="POST" action="{{ route('candidat.cvs.destroy', $cv) }}" data-confirm="Supprimer ce CV ?" data-confirm-btn="Supprimer">
            @csrf @method('DELETE')
            <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm cand-btn--icon-only" title="Supprimer">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </form>
        </div>
      </div>
    </div>
    @endforeach

    {{-- Documents --}}
    @foreach($documents as $doc)
    <div class="cand-card doc-item" data-type="type-{{ Str::slug($doc->type->nom) }}" style="margin-bottom:0">
      <div style="display:flex;align-items:flex-start;gap:12px">
        <div style="width:42px;height:42px;border-radius:8px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0">
          @if($doc->estImage())
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          @else
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          @endif
        </div>
        <div style="flex:1;min-width:0">
          <p style="font-weight:700;color:#042C53;margin:0;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $doc->nom }}</p>
          <p style="font-size:12px;color:#64748b;margin:2px 0 4px">{{ $doc->created_at->format('d/m/Y') }}</p>
          <span style="display:inline-block;font-size:11px;background:#F0FDF4;color:#16A34A;border-radius:4px;padding:1px 7px;font-weight:600">{{ $doc->type->nom }}</span>
        </div>
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:14px">
        <a href="{{ asset('storage/'.$doc->fichier) }}" target="_blank" rel="noopener"
           class="cand-btn cand-btn--outline cand-btn--sm" title="Ouvrir">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          Ouvrir
        </a>
        <div style="display:flex;gap:6px">
          <a href="{{ route('candidat.documents.edit', $doc) }}" class="cand-btn cand-btn--outline cand-btn--sm">Modifier</a>
          <form method="POST" action="{{ route('candidat.documents.destroy', $doc->id) }}"
                data-confirm="Supprimer ce document ?" data-confirm-btn="Supprimer">
            @csrf @method('DELETE')
            <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm cand-btn--icon-only" title="Supprimer">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </form>
        </div>
      </div>
    </div>
    @endforeach

  </div>

  <p id="no-results" style="display:none;text-align:center;color:#94a3b8;padding:32px 0;font-size:14px">Aucun élément pour ce filtre.</p>

@endif

<style>
.doc-filter {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 14px; border-radius: 20px; border: 1.5px solid #e2e8f0;
  background: #fff; font-size: 13px; font-weight: 500; color: #64748b;
  cursor: pointer; transition: all .15s;
}
.doc-filter:hover { border-color: #185FA5; color: #185FA5; }
.doc-filter--active { background: #185FA5; border-color: #185FA5; color: #fff; }
.doc-filter--active .doc-filter__count { background: rgba(255,255,255,.25); color: #fff; }
.doc-filter__count {
  background: #f1f5f9; color: #64748b; border-radius: 10px;
  padding: 1px 7px; font-size: 11px; font-weight: 700;
}
</style>

<script>
document.querySelectorAll('.doc-filter').forEach(btn => {
  btn.addEventListener('click', function () {
    document.querySelectorAll('.doc-filter').forEach(b => b.classList.remove('doc-filter--active'));
    this.classList.add('doc-filter--active');

    const filter = this.dataset.filter;
    let visible = 0;

    document.querySelectorAll('.doc-item').forEach(item => {
      const match = filter === 'tous' || item.dataset.type === filter;
      item.style.display = match ? '' : 'none';
      if (match) visible++;
    });

    document.getElementById('no-results').style.display = visible === 0 ? 'block' : 'none';
  });
});
</script>

@endsection
