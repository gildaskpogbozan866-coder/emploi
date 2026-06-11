{{--
  Barre de recherche/filtre générique.
  Usage : @include('partials._search-bar', ['route' => 'recruteur.offres', 'filters' => [...]])
  Chaque filtre : ['name' => 'statut', 'placeholder'|'label' => '...', 'options' => ['val' => 'Label', ...] (optionnel)]
--}}
@php $hasActive = collect($filters ?? [])->filter(fn($f) => request()->filled($f['name']))->isNotEmpty() || request()->filled('q'); @endphp
<form method="GET" action="{{ route($route) }}"
      style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:18px;align-items:center">
  @if($searchable ?? true)
  <input type="text" name="q" value="{{ request('q') }}"
         placeholder="{{ $placeholder ?? 'Rechercher…' }}"
         style="flex:1;min-width:180px;padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px">
  @endif

  @foreach($filters ?? [] as $filter)
    @if(isset($filter['options']))
    <select name="{{ $filter['name'] }}"
            style="padding:8px 10px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;background:#fff">
      <option value="">{{ $filter['label'] ?? 'Tous' }}</option>
      @foreach($filter['options'] as $val => $label)
        <option value="{{ $val }}" {{ request($filter['name']) === (string)$val ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
    @endif
  @endforeach

  <button type="submit"
          style="padding:8px 16px;background:#185FA5;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap">
    Filtrer
  </button>
  @if($hasActive)
    <a href="{{ route($route) }}"
       style="padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;color:#64748b;text-decoration:none;white-space:nowrap">
      ✕ Réinitialiser
    </a>
  @endif
</form>
