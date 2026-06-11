@props(['name' => 'competences', 'selected' => collect()])

@php
use App\Models\Competence;
$allCompetences = Competence::orderBy('nom')->get();
// Normalise selected en collection de chaînes
$selectedNoms = collect($selected)->map(fn($v) => is_string($v) ? $v : (is_object($v) ? $v->nom : (string)$v));
@endphp

@include('partials._jquery-cdn')
@once
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
.select2-container--default .select2-selection--multiple{border:1.5px solid #d1d5db!important;border-radius:8px!important;min-height:42px!important;padding:3px 6px!important}
.select2-container--default.select2-container--focus .select2-selection--multiple{border-color:#6366f1!important}
.select2-container--default .select2-selection--multiple .select2-selection__choice{background:#dbeafe;border:1px solid #bfdbfe;border-radius:5px;color:#1e40af;font-size:12.5px;padding:2px 8px}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove{color:#3b82f6;margin-right:4px}
.select2-dropdown{border:1.5px solid #d1d5db!important;border-radius:8px!important;box-shadow:0 4px 12px rgba(0,0,0,.08)}
.select2-results__option--highlighted{background:#dbeafe!important;color:#1e40af!important}
.select2-search--dropdown .select2-search__field{border:1px solid #e2e8f0;border-radius:6px;padding:6px 10px;font-size:13px}
</style>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/fr.js"></script>
@endonce

<select id="csel-{{ $name }}" name="{{ $name }}[]" multiple style="width:100%">
    @foreach($allCompetences as $comp)
        <option value="{{ $comp->nom }}"
            {{ $selectedNoms->contains($comp->nom) ? 'selected' : '' }}>
            {{ $comp->nom }}
        </option>
    @endforeach
    {{-- Options old() qui n'existent pas encore en base --}}
    @foreach($selectedNoms->diff($allCompetences->pluck('nom')) as $nouveau)
        <option value="{{ $nouveau }}" selected>{{ $nouveau }}</option>
    @endforeach
</select>

<script>
(function () {
    function boot() {
        $('#csel-{{ $name }}').select2({
            language: 'fr',
            tags: true,
            placeholder: 'Rechercher ou ajouter une compétence…',
            allowClear: true,
            tokenSeparators: [','],
            createTag: function (params) {
                var term = $.trim(params.term);
                if (!term) return null;
                return { id: term, text: term + ' (nouveau)', newTag: true };
            },
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
</script>
