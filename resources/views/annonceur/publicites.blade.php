@extends('layouts.annonceur')
@section('title', 'Mes annonces | Espace Annonceur')

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Mes annonces</h1>
    <p class="cand-page-header__sub">Soumettez et suivez vos affichages publicitaires.</p>
  </div>
  <div class="cand-page-header__actions">
    <button onclick="openModal()" class="cand-btn cand-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Nouvelle annonce
    </button>
  </div>
</div>

@if(session('success'))
  <div style="background:#f0fff4;border:1px solid #9ae6b4;border-radius:10px;padding:12px 16px;color:#276749;font-size:13.5px;font-weight:600;margin-bottom:20px;display:flex;align-items:center;gap:10px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
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
      <p class="cand-empty__text">Cliquez sur « Nouvelle annonce » pour soumettre votre première publicité.</p>
      <button onclick="openModal()" class="cand-btn cand-btn--yellow" style="margin-top:14px">Soumettre une annonce</button>
    </div>
  </div>
@else
  @foreach($publicites as $pub)
  <div class="cand-card" style="margin-bottom:12px">
    <div style="display:flex;align-items:flex-start;gap:14px;flex-wrap:wrap">
      <img src="{{ asset('storage/' . $pub->image) }}" alt="{{ $pub->titre }}"
           style="width:80px;height:60px;object-fit:cover;border-radius:8px;flex-shrink:0;border:1px solid #e2e8f0">
      <div style="flex:1;min-width:0">
        <p style="font-weight:700;color:#042C53;margin:0 0 6px;font-size:15px">{{ $pub->titre }}</p>
        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px">
          <span class="cand-badge cand-badge--{{ $pub->statut_badge }}">{{ $pub->statut_label }}</span>
          @if($pub->plan)
            <span class="cand-badge cand-badge--blue">{{ $pub->plan->name }}</span>
          @endif
          @if($pub->date_debut || $pub->date_fin)
            <span class="cand-badge cand-badge--gray">
              {{ $pub->date_debut?->format('d/m/Y') ?? '-' }} → {{ $pub->date_fin?->format('d/m/Y') ?? '∞' }}
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

{{-- ════════ MODAL NOUVELLE ANNONCE ════════ --}}
<div id="pubModal" style="display:none;position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,.55);overflow-y:auto;padding:40px 16px">
  <div style="max-width:680px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.3)">

    {{-- En-tête modal --}}
    <div style="background:linear-gradient(135deg,#042C53,#185FA5);padding:24px 28px;display:flex;align-items:center;justify-content:space-between">
      <div>
        <h2 style="color:#fff;font-size:1.2rem;font-weight:800;margin:0">Publier une Affiche Publicitaire</h2>
        <p style="color:rgba(255,255,255,.75);font-size:13px;margin:4px 0 0">Remplissez le formulaire puis choisissez votre plan</p>
      </div>
      <button onclick="closeModal()" style="background:rgba(255,255,255,.15);border:none;border-radius:8px;padding:8px;cursor:pointer;color:#fff;display:flex;align-items:center;justify-content:center">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <form method="POST" action="{{ route('annonceur.publicites.store') }}" enctype="multipart/form-data" style="padding:28px">
      @csrf

      @if($errors->any())
        <div style="background:#fff5f5;border:1px solid #feb2b2;border-radius:8px;padding:12px 14px;color:#c53030;font-size:13px;margin-bottom:20px">
          <ul style="margin:0;padding-left:16px">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      {{-- Titre --}}
      <div class="cand-form-group">
        <label class="cand-form-label">Titre de l'annonce <span class="req">*</span></label>
        <input class="cand-form-input" type="text" name="titre" value="{{ old('titre') }}"
               placeholder="Ex : Soldes été 2026, Boutique Cotonou" required>
        @error('titre')<p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
      </div>

      {{-- Lien --}}
      <div class="cand-form-group">
        <label class="cand-form-label">Lien cliquable (URL)</label>
        <input class="cand-form-input" type="url" name="lien" value="{{ old('lien') }}"
               placeholder="https://votre-site.com">
        @error('lien')<p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
      </div>

      {{-- Image --}}
      <div class="cand-form-group">
        <label class="cand-form-label">Image de l'affiche <span class="req">*</span></label>
        <input class="cand-form-input" type="file" name="image"
               accept="image/jpeg,image/png,image/webp,image/gif" required
               style="padding:8px 10px;cursor:pointer" id="imgInput" onchange="previewImg(this)">
        <p style="font-size:11.5px;color:#94a3b8;margin:4px 0 0">JPG, PNG, WebP ou GIF, max 5 Mo</p>
        @error('image')<p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
        <div id="imgPreview" style="margin-top:10px;display:none;background:#f8fafc;border-radius:8px;padding:6px;border:1px solid #e2e8f0">
          <img id="imgPreviewEl" src="" alt="Aperçu" style="max-height:140px;width:100%;border-radius:6px;object-fit:contain;display:block">
        </div>
      </div>

      {{-- Note --}}
      <div class="cand-form-group">
        <label class="cand-form-label">Note pour l'équipe (optionnel)</label>
        <textarea class="cand-form-input" name="note_annonceur" rows="2" style="resize:vertical"
                  placeholder="Informations supplémentaires pour la modération…">{{ old('note_annonceur') }}</textarea>
      </div>

      {{-- ── Plans de diffusion ── --}}
      <div style="margin:24px 0 0">
        <p style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#374151;margin:0 0 14px">
          Choisissez votre plan de diffusion <span class="req">*</span>
        </p>

        @error('plan_id')
          <p style="color:#dc2626;font-size:12px;margin:0 0 10px">{{ $message }}</p>
        @enderror

        @if($plans->isEmpty())
          <p style="color:#94a3b8;font-size:13px;font-style:italic">Aucun plan disponible pour le moment. Contactez l'administration.</p>
        @else
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px">
            @foreach($plans as $plan)
            <label style="cursor:pointer">
              <input type="radio" name="plan_id" value="{{ $plan->id }}"
                     {{ old('plan_id') == $plan->id ? 'checked' : '' }}
                     style="display:none" class="plan-radio"
                     onchange="highlightPlan(this)">
              <div class="plan-card" id="plan-card-{{ $plan->id }}"
                   style="border:2px solid #e2e8f0;border-radius:12px;padding:16px 14px;transition:border-color .2s,background .2s;
                          {{ old('plan_id') == $plan->id ? 'border-color:#185FA5;background:#eff6ff' : '' }}">
                <p style="font-weight:800;color:#042C53;font-size:15px;margin:0 0 4px">{{ $plan->name }}</p>
                <p style="font-size:22px;font-weight:800;color:#185FA5;margin:0 0 4px">
                  @if($plan->is_free || $plan->price <= 0) Gratuit
                  @else {{ number_format($plan->price, 0, ',', ' ') }} <span style="font-size:13px;font-weight:600">XOF</span>
                  @endif
                </p>
                @if($plan->duration_days)
                  <p style="font-size:12px;color:#64748b;margin:0">{{ $plan->duration_days }} jours de diffusion</p>
                @else
                  <p style="font-size:12px;color:#64748b;margin:0">Durée illimitée</p>
                @endif
              </div>
            </label>
            @endforeach
          </div>
        @endif
      </div>

      <div style="margin-top:28px;display:flex;gap:12px;justify-content:flex-end">
        <button type="button" onclick="closeModal()" class="cand-btn cand-btn--outline">Annuler</button>
        <button type="submit" class="cand-btn cand-btn--yellow">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Continuer vers le paiement
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
function openModal() {
  document.getElementById('pubModal').style.display = 'block';
  document.body.style.overflow = 'hidden';
}
function closeModal() {
  document.getElementById('pubModal').style.display = 'none';
  document.body.style.overflow = '';
}
document.getElementById('pubModal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});
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
function highlightPlan(radio) {
  document.querySelectorAll('.plan-card').forEach(c => {
    c.style.borderColor = '#e2e8f0';
    c.style.background  = '';
  });
  const card = document.getElementById('plan-card-' + radio.value);
  if (card) {
    card.style.borderColor = '#185FA5';
    card.style.background  = '#eff6ff';
  }
}
// Ouvrir automatiquement si erreurs de validation
@if($errors->any())
  openModal();
@endif
</script>
@endsection
