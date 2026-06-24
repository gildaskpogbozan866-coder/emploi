@extends('layouts.app')
@section('title', 'Affichages Publicitaires | Emploi Bouge Bénin')
@section('description', 'Découvrez les annonces et affichages publicitaires des entreprises partenaires sur Emploi Bouge Bénin.')

@section('content')

<section style="background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);padding:60px 0 48px">
  <div class="container" style="text-align:center">
    <span style="display:inline-flex;align-items:center;gap:7px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#F5C842;margin-bottom:14px">
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
      Espace publicitaire
    </span>
    <h1 style="font-size:2.2rem;font-weight:800;color:#fff;margin:0 0 12px;line-height:1.25">Affichages Publicitaires</h1>
    <p style="color:rgba(255,255,255,.75);font-size:15px;max-width:520px;margin:0 auto 28px;line-height:1.65">
      Découvrez les annonces de nos partenaires et entreprises présentes sur la plateforme.
    </p>
    <a href="{{ route('auth.inscription') }}?role=annonceur"
       style="display:inline-flex;align-items:center;gap:8px;background:#F5C842;color:#042C53;padding:13px 26px;border-radius:10px;font-weight:800;font-size:14px;text-decoration:none">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Publier une Affiche Publicitaire
    </a>
  </div>
</section>

<section style="padding:56px 0">
  <div class="container">

    @if($publicites->isEmpty())
      <div style="text-align:center;padding:64px 20px;color:#94a3b8">
        <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2" style="margin:0 auto 16px;display:block;opacity:.4"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        <p style="font-size:15px;font-weight:600;margin:0 0 6px;color:#374151">Aucune publicité active pour le moment</p>
        <p style="font-size:13.5px;margin:0">Revenez bientôt !</p>
      </div>
    @else
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px">
        @foreach($publicites as $pub)
        <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.05);transition:transform .2s,box-shadow .2s"
             onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 28px rgba(0,0,0,.1)'"
             onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.05)'">

          @if($pub->lien)
            <a href="{{ $pub->lien }}" target="_blank" rel="noopener noreferrer" style="display:block">
          @endif
              <img src="{{ asset('storage/' . $pub->image) }}" alt="{{ $pub->titre }}"
                   style="width:100%;max-height:400px;object-fit:contain;display:block;background:#f8fafc">
          @if($pub->lien)
            </a>
          @endif

          <div style="padding:16px 18px">
            <p style="font-weight:700;color:#042C53;font-size:15px;margin:0 0 8px;line-height:1.35">{{ $pub->titre }}</p>

            @if($pub->date_debut || $pub->date_fin)
              <p style="font-size:12px;color:#94a3b8;margin:0 0 10px;display:flex;align-items:center;gap:5px">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $pub->date_debut?->format('d/m/Y') ?? '-' }} → {{ $pub->date_fin?->format('d/m/Y') ?? '∞' }}
              </p>
            @endif

            @if($pub->lien)
              <a href="{{ $pub->lien }}" target="_blank" rel="noopener noreferrer"
                 style="display:inline-flex;align-items:center;gap:6px;background:#042C53;color:#fff;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Visiter le site
              </a>
            @endif
          </div>
        </div>
        @endforeach
      </div>
    @endif

  </div>
</section>

@endsection
