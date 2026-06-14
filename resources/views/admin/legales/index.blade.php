@extends('layouts.admin')
@section('title', 'Pages légales')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Pages légales</h1>
    <p>Gérez le contenu des mentions légales, CGV et politique de confidentialité.</p>
  </div>
</div>

@if(session('success'))
<div class="alert alert--success" style="margin-bottom:24px">{{ session('success') }}</div>
@endif

<div class="adm-card-list">
  @foreach($pages as $slug => $page)
  <div class="adm-card-list__row">

    <div class="adm-card-list__icon">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
    </div>

    <div class="adm-card-list__body">
      <p class="adm-card-list__title">{{ $page->titre }}</p>
      <p class="adm-card-list__meta">
        /legale/{{ $page->slug }}
        @if($page->exists && $page->updated_at)
          &nbsp;·&nbsp;Mis à jour le {{ $page->updated_at->format('d/m/Y') }}
        @else
          &nbsp;·&nbsp;<span class="badge badge--warning">Non renseigné</span>
        @endif
      </p>
    </div>

    <div class="adm-card-list__actions">
      <a href="{{ route('legale.show', $page->slug) }}" target="_blank" class="adm-btn adm-btn--outline adm-btn--sm">
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        Voir
      </a>
      <a href="{{ route('admin.legales.edit', $page->slug) }}" class="adm-btn adm-btn--yellow adm-btn--sm">
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Modifier
      </a>
    </div>

  </div>
  @endforeach
</div>
@endsection
