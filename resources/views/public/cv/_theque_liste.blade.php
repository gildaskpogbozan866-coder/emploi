{{-- Cards CV + Documents --}}
<div class="cvt-list">
@forelse($cvs as $item)
  @php
    $isDoc  = !empty($item->_is_document);
    $dispo  = null;
    $url    = $isDoc ? route('document.public.detail', $item->id) : route('cv.public.detail', $item);
  @endphp

  <a href="{{ $url }}" style="display:block;background:#fff;border:1.5px solid #e8edf3;border-radius:16px;padding:20px;text-decoration:none;transition:box-shadow .18s,transform .18s;box-shadow:0 2px 8px rgba(4,44,83,.06)"
     onmouseover="this.style.boxShadow='0 8px 28px rgba(4,44,83,.13)';this.style.transform='translateY(-2px)'"
     onmouseout="this.style.boxShadow='0 2px 8px rgba(4,44,83,.06)';this.style.transform='none'">

    {{-- En-tête : avatar + nom de poste + dispo --}}
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px">

      {{-- Avatar --}}
      <div style="flex-shrink:0;width:54px;height:54px;border-radius:50%;overflow:hidden;border:2px solid #e8edf3;background:linear-gradient(135deg,#e8f0fe,#dbeafe);display:flex;align-items:center;justify-content:center">
        @if($isDoc)
          <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        @elseif($item->photo)
          <img src="{{ asset('storage/' . $item->photo) }}" alt="" style="width:100%;height:100%;object-fit:cover;filter:blur(6px);transform:scale(1.1)">
        @else
          <span style="font-size:1.4rem;font-weight:800;color:#185FA5">{{ mb_strtoupper(mb_substr($item->candidat?->prenom ?? '?', 0, 1)) }}</span>
        @endif
      </div>

      {{-- Titre + badge --}}
      <div style="flex:1;min-width:0">
        <div style="font-size:14.5px;font-weight:700;color:#042C53;line-height:1.3;margin-bottom:5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
          {{ $isDoc ? ($item->titre_poste ?? '') : ($item->metier ?? 'Profil candidat') }}
        </div>
        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
          @if($isDoc)
            <span style="font-size:10.5px;font-weight:700;padding:2px 8px;border-radius:20px;background:#f0f9ff;color:#0284c7;border:1px solid #bae6fd">{{ $item->type_label }}</span>
          @else
            @if($dispo)
              <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#475569">
                <span style="width:7px;height:7px;border-radius:50%;background:{{ $dispo->couleur }};flex-shrink:0"></span>
                {{ $dispo->libelle }}
              </span>
            @endif
            @if($item->plan === 'premium')
              <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:#fef9c3;color:#854d0e;border:1px solid #fde68a">★ Premium</span>
            @endif
          @endif
        </div>
      </div>
    </div>

    {{-- Infos --}}
    <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:16px">
      @if(!empty($item->pays) || !empty($item->ville))
        <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:#64748b">
          <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
          {{ $item->ville ?? $item->pays }}
        </div>
      @endif
      @if($item->competences)
        <div style="font-size:12px;color:#64748b;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
          {{ Str::limit($item->competences, 90) }}
        </div>
      @endif
    </div>

    {{-- CTA --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid #f1f5f9">
      @if(!$isDoc && $item->langues)
        <span style="font-size:11px;color:#94a3b8">🌐 {{ Str::limit($item->langues, 30) }}</span>
      @else
        <span></span>
      @endif
      <span style="font-size:12.5px;font-weight:700;color:#185FA5;display:inline-flex;align-items:center;gap:4px">
        {{ $isDoc ? 'Voir le document' : 'Voir le profil' }}
        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </span>
    </div>

  </a>

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
