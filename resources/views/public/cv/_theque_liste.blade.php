{{-- Cards CV --}}
<div class="cvt-list">
@forelse($cvs as $cv)
  <div class="cvt-card">
    <div class="cvt-card__inner">
      <div class="cvt-card__body">

        <div class="cvt-card__photo">
          @if($cv->photo)
            <img src="{{ asset('storage/' . $cv->photo) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
          @else
            <span style="font-family:var(--font-body);font-size:1.6rem;font-weight:700;color:#185FA5;">
              {{ mb_strtoupper(mb_substr($cv->candidat?->prenom ?? '?', 0, 1)) }}
            </span>
          @endif
        </div>

        <div class="cvt-card__info">
          <div class="cvt-card__row">
            <span class="cvt-card__label">Poste :</span>
            <span class="cvt-card__val">{{ $cv->titre_poste }}</span>
            @if($cv->plan === 'premium')
              <span class="cvt-card__premium-badge">Premium</span>
            @endif
          </div>

          @if($cv->pays)
          <div class="cvt-card__row">
            <span class="cvt-card__label">Pays :</span>
            <span class="cvt-card__val">{{ $cv->pays }}</span>
          </div>
          @endif

          @if($cv->langues)
          <div class="cvt-card__row">
            <span class="cvt-card__label">Langues :</span>
            <span class="cvt-card__val">{{ $cv->langues }}</span>
          </div>
          @endif

          @if($cv->competences)
          <div class="cvt-card__row">
            <span class="cvt-card__label">Compétences :</span>
            <span class="cvt-card__val">{{ Str::limit($cv->competences, 90) }}</span>
          </div>
          @endif

          @if($cv->experience)
          <div class="cvt-card__row">
            <span class="cvt-card__label">Expérience :</span>
            <span class="cvt-card__val">{{ Str::limit($cv->experience, 80) }}</span>
          </div>
          @endif
        </div>

      </div>

      <div class="cvt-card__footer">
        <a href="{{ route('cv.public.detail', $cv) }}" class="cvt-card__btn">
          Voir ce CV
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
      </div>
    </div>
  </div>
@empty
  <div class="cvt-empty">
    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    <p class="cvt-empty__title">Aucun profil trouvé</p>
    <p class="cvt-empty__sub">Essayez d'autres critères de recherche ou revenez bientôt, de nouveaux talents s'inscrivent chaque jour.</p>
    <a href="{{ route('cv.public.theque') }}" class="cvt-card__btn" style="display:inline-flex;margin-top:16px;">Voir tous les profils</a>
  </div>
@endforelse
</div>

{{-- Pagination --}}
@if($cvs->hasPages())
  <div class="cvt-pagination">
    {{ $cvs->withQueryString()->links() }}
  </div>
@endif
