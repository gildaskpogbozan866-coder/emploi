@extends('layouts.admin')
@section('title', $label . ' — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>{{ $label }}</h1>
    <p id="ref-count">{{ $items->count() }} entrée{{ $items->count() > 1 ? 's' : '' }}</p>
  </div>
  <div class="adm-topbar__actions">
    <a href="{{ route($routeCreate) }}" class="adm-btn adm-btn--yellow">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Ajouter
    </a>
  </div>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          @if($hasCode)
            <th>Code</th>
            <th>Libellé</th>
          @else
            <th>Nom</th>
            @if($hasDesc)<th>Description</th>@endif
          @endif
          @if($hasOrdre)<th style="width:80px">Ordre</th>@endif
          <th style="width:110px">Candidats</th>
          <th style="width:140px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr id="ref-row-{{ $item->id }}">
          @if($hasCode)
            <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:12px">{{ $item->code }}</code></td>
            <td style="font-weight:600">{{ $item->libelle }}</td>
          @else
            <td style="font-weight:600">{{ $item->nom }}</td>
            @if($hasDesc)<td style="color:#64748b;font-size:13px">{{ $item->description ?? '—' }}</td>@endif
          @endif
          @if($hasOrdre)<td style="color:#64748b;text-align:center">{{ $item->ordre }}</td>@endif
          <td style="text-align:center">
            <span class="adm-badge adm-badge--gray">{{ $item->{$countKey} ?? 0 }}</span>
          </td>
          <td>
            <div class="actions">
              <a href="{{ route($routeEdit, $item) }}" class="adm-btn adm-btn--outline adm-btn--sm">Modifier</a>
              <button type="button" class="adm-btn adm-btn--danger adm-btn--sm js-delete-ref"
                      data-url="{{ route($routeDestroy, $item) }}"
                      data-row="ref-row-{{ $item->id }}"
                      data-singular="{{ $singular }}">
                Supprimer
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="10">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              <h3>Aucun(e) {{ $singular }}</h3>
              <p>Cliquez sur "Ajouter" pour créer le premier.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('scripts')
<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;

document.addEventListener('click', async function (e) {
    const btn = e.target.closest('.js-delete-ref');
    if (!btn) return;

    const url      = btn.dataset.url;
    const rowId    = btn.dataset.row;
    const singular = btn.dataset.singular;

    const { isConfirmed } = await Swal.fire({
        title: 'Supprimer ce(tte) ' + singular + ' ?',
        text: 'Cette action est irréversible.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        reverseButtons: true,
        focusCancel: true,
    });
    if (!isConfirmed) return;

    const r = await fetch(url, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const data = await r.json().catch(() => ({}));

    if (r.ok) {
        const row = document.getElementById(rowId);
        row.style.transition = 'opacity .3s';
        row.style.opacity = '0';
        setTimeout(() => {
            row.remove();
            const el = document.getElementById('ref-count');
            if (el) {
                const n = Math.max(0, (parseInt(el.textContent) || 1) - 1);
                el.textContent = n + ' entrée' + (n > 1 ? 's' : '');
            }
        }, 300);
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Supprimé avec succès', showConfirmButton: false, timer: 2500, timerProgressBar: true });
    } else {
        Swal.fire({ icon: 'error', title: 'Impossible de supprimer', text: data.message ?? 'Cet élément est peut-être utilisé ailleurs.', confirmButtonColor: '#ef4444' });
    }
});
</script>
@endsection
