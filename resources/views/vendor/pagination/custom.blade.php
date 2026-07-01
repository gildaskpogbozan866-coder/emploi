@if ($paginator->hasPages())
@php
  $btnBase  = 'display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;border-radius:8px;font-size:13.5px;font-weight:600;text-decoration:none;border:1.5px solid #e2e8f0;cursor:pointer;transition:all .15s';
  $btnActive = $btnBase . ';background:#042C53;color:#fff;border-color:#042C53;';
  $btnNormal = $btnBase . ';background:#fff;color:#042C53;';
  $btnDisabled = $btnBase . ';background:#f8fafc;color:#cbd5e1;cursor:default;';
@endphp
<nav style="display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap">

  {{-- Précédent --}}
  @if ($paginator->onFirstPage())
    <span style="{{ $btnDisabled }}">&laquo;</span>
  @else
    <a href="{{ $paginator->previousPageUrl() }}" style="{{ $btnNormal }}">&laquo;</a>
  @endif

  {{-- Pages --}}
  @foreach ($elements as $element)
    @if (is_string($element))
      <span style="{{ $btnDisabled }}">…</span>
    @endif
    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <span style="{{ $btnActive }}">{{ $page }}</span>
        @else
          <a href="{{ $url }}" style="{{ $btnNormal }}">{{ $page }}</a>
        @endif
      @endforeach
    @endif
  @endforeach

  {{-- Suivant --}}
  @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" style="{{ $btnNormal }}">&raquo;</a>
  @else
    <span style="{{ $btnDisabled }}">&raquo;</span>
  @endif

</nav>
@endif
