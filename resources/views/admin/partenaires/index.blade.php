@extends('layouts.admin')
@section('title', 'Partenaires & Logos | Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Partenaires & Logos de confiance</h1>
    <p>Ces logos apparaissent sur la page d'accueil dans la section "Ils nous font confiance".</p>
  </div>
</div>

@if(session('success'))
  <div class="adm-alert adm-alert--success" style="margin-bottom:16px">{{ session('success') }}</div>
@endif

{{-- Formulaire d'ajout --}}
<div class="adm-card" style="max-width:600px;margin-bottom:28px">
  <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Ajouter un partenaire</h3>
  </div>
  <form method="POST" action="{{ route('admin.partenaires.store') }}" enctype="multipart/form-data" style="padding:20px 24px;display:flex;flex-direction:column;gap:14px">
    @csrf
    <div>
      <label class="adm-label">Nom du partenaire *</label>
      <input type="text" name="nom" class="adm-input" placeholder="Ex: Orange Bénin" required>
    </div>
    <div>
      <label class="adm-label">Logo (PNG, JPG, SVG) *</label>
      <input type="file" name="logo" class="adm-input" accept="image/*" required>
      <p style="font-size:11px;color:#94a3b8;margin:4px 0 0">Max 2 Mo. Privilégiez un fond transparent (PNG) ou un fond blanc.</p>
    </div>
    <div>
      <label class="adm-label">Lien vers le site (optionnel)</label>
      <input type="url" name="lien" class="adm-input" placeholder="https://…">
    </div>
    <div>
      <label class="adm-label">Ordre d'affichage</label>
      <input type="number" name="ordre" class="adm-input" value="0" min="0" style="max-width:120px">
    </div>
    <div>
      <button type="submit" class="adm-btn adm-btn--blue">Ajouter</button>
    </div>
  </form>
</div>

{{-- Liste des partenaires --}}
<div class="adm-card" style="max-width:900px">
  <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">
      Partenaires enregistrés ({{ $partenaires->count() }})
    </h3>
  </div>
  @if($partenaires->isEmpty())
    <div style="padding:32px 24px;text-align:center;color:#94a3b8">Aucun partenaire ajouté pour l'instant.</div>
  @else
  <div style="padding:12px 0">
    @foreach($partenaires as $p)
    <div style="display:flex;align-items:center;gap:16px;padding:12px 24px;border-bottom:1px solid #f8fafc">

      {{-- Logo preview --}}
      <div style="width:80px;height:44px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
        <img src="{{ asset('storage/'.$p->logo) }}" alt="{{ $p->nom }}" style="max-width:72px;max-height:36px;object-fit:contain">
      </div>

      {{-- Infos --}}
      <div style="flex:1;min-width:0">
        <div style="font-weight:600;color:#042C53;font-size:14px">{{ $p->nom }}</div>
        @if($p->lien)
          <div style="font-size:12px;color:#94a3b8;truncate">{{ $p->lien }}</div>
        @endif
      </div>

      {{-- Statut badge --}}
      <span class="adm-badge {{ $p->actif ? 'adm-badge--green' : 'adm-badge--gray' }}">
        {{ $p->actif ? 'Actif' : 'Masqué' }}
      </span>

      {{-- Actions --}}
      <button onclick="openEdit({{ $p->id }})" class="adm-btn adm-btn--outline adm-btn--sm">Modifier</button>
      <form method="POST" action="{{ route('admin.partenaires.destroy', $p) }}" onsubmit="return confirm('Supprimer ce partenaire ?')">
        @csrf @method('DELETE')
        <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Suppr.</button>
      </form>
    </div>

    {{-- Modal inline d'édition --}}
    <div id="edit-{{ $p->id }}" style="display:none;background:#f8fafc;border-left:3px solid #185FA5;padding:16px 24px;margin:0 24px 8px">
      <form method="POST" action="{{ route('admin.partenaires.update', $p) }}" enctype="multipart/form-data" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
        @csrf @method('PUT')
        <div>
          <label class="adm-label" style="font-size:11px">Nom</label>
          <input type="text" name="nom" class="adm-input" value="{{ $p->nom }}" required style="width:180px">
        </div>
        <div>
          <label class="adm-label" style="font-size:11px">Nouveau logo</label>
          <input type="file" name="logo" class="adm-input" accept="image/*">
        </div>
        <div>
          <label class="adm-label" style="font-size:11px">Lien</label>
          <input type="url" name="lien" class="adm-input" value="{{ $p->lien }}" style="width:200px">
        </div>
        <div>
          <label class="adm-label" style="font-size:11px">Ordre</label>
          <input type="number" name="ordre" class="adm-input" value="{{ $p->ordre }}" min="0" style="width:80px">
        </div>
        <div style="display:flex;align-items:center;gap:6px;padding-bottom:2px">
          <input type="checkbox" name="actif" value="1" id="actif-{{ $p->id }}" {{ $p->actif ? 'checked' : '' }}>
          <label for="actif-{{ $p->id }}" style="font-size:13px;font-weight:500;color:#374151">Actif</label>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="adm-btn adm-btn--blue adm-btn--sm">Enregistrer</button>
          <button type="button" onclick="closeEdit({{ $p->id }})" class="adm-btn adm-btn--outline adm-btn--sm">Annuler</button>
        </div>
      </form>
    </div>
    @endforeach
  </div>
  @endif
</div>

@endsection

@section('scripts')
<script>
function openEdit(id) {
  document.querySelectorAll('[id^="edit-"]').forEach(el => el.style.display = 'none');
  document.getElementById('edit-' + id).style.display = 'block';
}
function closeEdit(id) {
  document.getElementById('edit-' + id).style.display = 'none';
}
</script>
@endsection
