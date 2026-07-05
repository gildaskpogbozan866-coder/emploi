@props(['url'])
@php
    // Encodé en base64 (plutôt qu'un lien asset()) pour que le logo s'affiche
    // même quand l'hôte mail (ex: Mailtrap en local) ne peut pas atteindre APP_URL.
    $logoPath = public_path('images/Logo.png');
    $logoSrc  = is_file($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : asset('images/Logo.png');
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ $logoSrc }}" class="logo" alt="Emploi Bouge Bénin">
</a>
</td>
</tr>
