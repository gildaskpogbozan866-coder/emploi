@extends('layouts.annonceur')
@section('title', 'Mes annonces — Espace Annonceur')

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Mes annonces</h1>
    <p class="cand-page-header__sub">Soumettez et suivez vos annonces publicitaires.</p>
  </div>
</div>

<div class="ann-grid" style="display:grid;grid-template-columns:1fr 360px;gap:22px;align-items:start">

  {{-- ── Liste ── --}}
  <div>
    @if(session('success'))
      <div style="background:#f0fff4;border:1px solid #9ae6b4;border-radius:10px;padding:12px 16px;color:#276749;font-size:13.5px;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:10px">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
      </div>
    @endif

    @if($publicites->isEmpty())
      <div class="cand-card">
        <div class="cand-empty">
          <div class="cand-empty__icon">
            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
          </div>
          <p class="cand-empty__title">Aucune annonce</p>
          <p class="cand-empty__text">Remplissez le formulaire ci-contre pour soumettre votre première annonce.</p>
        </div>
      </div>
    @else
      @foreach($publicites as $pub)
      <div class="cand-card" style="margin-bottom:12px">
        <div style="display:flex;align-items:flex-start;gap:14px;flex-wrap:wrap">

          <img src="{{ asset('storage/' . $pub->image) }}"
               alt="{{ $pub->titre }}"
               style="width:80px;height:60px;object-fit:cover;border-radius:8px;flex-shrink:0;border:1px solid #e2e8f0">

          <div style="flex:1;min-width:0">
            <p style="font-weight:700;color:#042C53;margin:0 0 6px;font-size:15px">{{ $pub->titre }}</p>
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px">
              <span class="cand-badge cand-badge--{{ $pub->statut_badge }}">{{ $pub->statut_label }}</span>
              @if($pub->date_debut || $pub->date_fin)
                <span class="cand-badge cand-badge--gray">
                  {{ $pub->date_debut?->format('d/m/Y') ?? '—' }} → {{ $pub->date_fin?->format('d/m/Y') ?? '∞' }}
                </span>
              @endif
            </div>
            @if($pub->lien)
              <a href="{{ $pub->lien }}" target="_blank" rel="noopener noreferrer"
                 style="font-size:12px;color:#185FA5;display:block;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                {{ $pub->lien }}
              </a>
            @endif
            @if($pub->note_admin && $pub->statut === 'rejete')
              <p style="font-size:12.5px;color:#dc2626;margin:6px 0 0;background:#fff5f5;border-radius:6px;padding:6px 10px">
                <strong>Motif du rejet :</strong> {{ $pub->note_admin }}
              </p>
            @endif
            <p style="font-size:12px;color:#94a3b8;margin:6px 0 0">
              Soumise le {{ $pub->created_at->format('d/m/Y à H:i') }}
            </p>
          </div>

          @if(in_array($pub->statut, ['en_attente', 'rejete']))
            <div style="flex-shrink:0">
              <form method="POST" action="{{ route('annonceur.publicites.destroy', $pub) }}"
                    onsubmit="return confirm('Supprimer cette annonce ?')">
                @csrf @method('DELETE')
                <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm">Supprimer</button>
              </form>
            </div>
          @endif

        </div>
      </div>
      @endforeach
    @endif
  </div>

  {{-- ── Formulaire sticky ── --}}
  <div class="cand-card" style="position:sticky;top:80px">
    <div class="cand-card__head">
      <h2 class="cand-card__title">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nouvelle annonce
      </h2>
    </div>

    <form method="POST" action="{{ route('annonceur.publicites.store') }}" enctype="multipart/form-data">
      @csrf

      @if($errors->any())
        <div style="background:#fff5f5;border:1px solid #feb2b2;border-radius:8px;padding:12px 14px;color:#c53030;font-size:13px;margin-bottom:16px">
          <ul style="margin:0;padding-left:16px">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <div class="cand-form-group">
        <label class="cand-form-label">Titre <span class="req">*</span></label>
        <input class="cand-form-input" type="text" name="titre" value="{{ old('titre') }}"
               placeholder="Ex : Soldes été 2026 — Boutique Cotonou" required>
        @error('titre')<p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
      </div>

      <div class="cand-form-group">
        <label class="cand-form-label">Lien cliquable (URL)</label>
        <input class="cand-form-input" type="url" name="lien" value="{{ old('lien') }}"
               placeholder="https://votre-site.com">
        @error('lien')<p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
      </div>

      <div class="cand-form-group">
        <label class="cand-form-label">Image <span class="req">*</span></label>
        <input class="cand-form-input" type="file" name="image"
               accept="image/jpeg,image/png,image/webp,image/gif" required
               style="padding:8px 10px;cursor:pointer" id="imgInput" onchange="previewImg(this)">
        <p style="font-size:11.5px;color:#94a3b8;margin:4px 0 0">JPG, PNG, WebP ou GIF — max 5 Mo</p>
        @error('image')<p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
        <div id="imgPreview" style="margin-top:10px;display:none;background:#f8fafc;border-radius:8px;padding:6px;border:1px solid #e2e8f0">
          <img id="imgPreviewEl" src="" alt="Aperçu de votre annonce"
               style="max-height:140px;width:100%;border-radius:6px;object-fit:contain;display:block">
        </div>
      </div>

      <div class="cand-form-group">
        <label class="cand-form-label">Date de début</label>
        <input class="cand-form-input" type="date" name="date_debut" value="{{ old('date_debut') }}"
               min="{{ now()->toDateString() }}">
        @error('date_debut')<p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
      </div>

      <div class="cand-form-group">
        <label class="cand-form-label">Date de fin</label>
        <input class="cand-form-input" type="date" name="date_fin" value="{{ old('date_fin') }}"
               min="{{ now()->toDateString() }}">
        @error('date_fin')<p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
      </div>

      <div class="cand-form-group">
        <label class="cand-form-label">Note pour l'équipe (optionnel)</label>
        <textarea class="cand-form-input" name="note_annonceur" rows="2"
                  style="resize:vertical"
                  placeholder="Informations supplémentaires pour la modération…">{{ old('note_annonceur') }}</textarea>
        @error('note_annonceur')<p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
      </div>

      <div class="cand-form-actions">
        <button type="submit" class="cand-btn cand-btn--yellow" style="width:100%">
          Soumettre l'annonce
        </button>
      </div>
    </form>
  </div>

</div>

<style>
@media (max-width: 900px) {
  .ann-grid { grid-template-columns: 1fr !important; }
}
</style>
@endsection

@section('scripts')
<script>
function previewImg(input) {
  const preview = document.getElementById('imgPreview');
  const img     = document.getElementById('imgPreviewEl');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; preview.style.display = 'block'; };
    reader.readAsDataURL(input.files[0]);
  } else {
    preview.style.display = 'none';
  }
}
</script>
@endsection
