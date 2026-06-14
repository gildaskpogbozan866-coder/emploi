@extends('layouts.admin')
@section('title', 'FAQ')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>FAQ</h1>
    <p>Gérez les questions fréquentes affichées sur la page publique.</p>
  </div>
  <a href="{{ route('admin.faqs.create') }}" class="adm-btn adm-btn--yellow">
    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    Ajouter une question
  </a>
</div>

@if(session('success'))
<div class="alert alert--success" style="margin-bottom:20px">{{ session('success') }}</div>
@endif

@forelse($faqs as $categorie => $questions)
<div style="margin-bottom:32px">
  <h2 style="font-size:.8rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin:0 0 10px">{{ $categorie }}</h2>
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden">
    @foreach($questions as $i => $faq)
    <div style="display:flex;align-items:flex-start;gap:14px;padding:16px 20px;{{ !$loop->last ? 'border-bottom:1px solid #f1f5f9' : '' }};{{ !$faq->actif ? 'background:#fffbeb;' : '' }}">
      <div style="flex:1;min-width:0">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
          @if(!$faq->actif)
            <span style="font-size:11px;background:#fef3c7;color:#92400e;border-radius:20px;padding:2px 8px;font-weight:600">Masquée</span>
          @endif
          <p style="font-weight:700;color:#042C53;font-size:.9rem;margin:0">{{ $faq->question }}</p>
        </div>
        <p style="font-size:.85rem;color:#64748b;margin:0;line-height:1.55">{{ Str::limit($faq->reponse, 120) }}</p>
      </div>
      <div style="display:flex;gap:6px;flex-shrink:0;align-items:center">
        <span style="font-size:11.5px;color:#94a3b8;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:3px 8px">
          #{{ $faq->ordre }}
        </span>
        <form method="POST" action="{{ route('admin.faqs.toggle', $faq) }}" style="display:inline">
          @csrf @method('PATCH')
          <button type="submit" title="{{ $faq->actif ? 'Masquer' : 'Afficher' }}" class="adm-btn adm-btn--outline adm-btn--sm">
            @if($faq->actif)
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            @else
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
            @endif
          </button>
        </form>
        <a href="{{ route('admin.faqs.edit', $faq) }}" class="adm-btn adm-btn--outline adm-btn--sm">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </a>
        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" style="display:inline"
              onsubmit="return confirm('Supprimer cette question ?')">
          @csrf @method('DELETE')
          <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          </button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
</div>
@empty
<div style="text-align:center;padding:60px 20px;color:#94a3b8;background:#fff;border:1px solid #e2e8f0;border-radius:12px">
  <p>Aucune question pour l'instant.</p>
  <a href="{{ route('admin.faqs.create') }}" class="adm-btn adm-btn--yellow" style="margin-top:16px">Ajouter la première question</a>
</div>
@endforelse
@endsection
