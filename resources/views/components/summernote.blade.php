@props([
    'name',
    'value'       => '',
    'height'      => 280,
    'placeholder' => '',
])

@include('partials._jquery-cdn')
@once
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css">
<style>
.note-editor.note-frame{border:1.5px solid #d1d5db!important;border-radius:8px!important;overflow:hidden}
.note-toolbar{background:#f8fafc!important;border-bottom:1px solid #e2e8f0!important;padding:6px 8px!important}
.note-editing-area .note-editable{font-size:14px;line-height:1.6;color:#374151;padding:12px 14px!important}
.note-statusbar{background:#f8fafc!important;border-top:1px solid #e2e8f0!important}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-fr-FR.min.js"></script>
@endonce

<textarea id="sn-{{ $name }}" name="{{ $name }}">{{ $value }}</textarea>
<script>
(function () {
    function boot() {
        $('#sn-{{ $name }}').summernote({
            lang: 'fr-FR',
            height: {{ $height }},
            @if($placeholder)placeholder: '{{ addslashes($placeholder) }}',@endif
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para',  ['ul', 'ol', 'paragraph']],
                ['view',  ['fullscreen']],
            ],
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
</script>
