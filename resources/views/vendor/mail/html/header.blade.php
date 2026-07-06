@props(['url'])
@php
    // Encodé en base64 (plutôt qu'un lien asset()) pour que le logo s'affiche
    // même quand l'hôte mail (ex: Mailtrap en local) ne peut pas atteindre APP_URL.
    // Utilise une version redimensionnée (303x96, ~10 Ko) et non le Logo.png
    // du site (887x281, ~154 Ko) : le logo n'est affiché qu'à 48px de haut en
    // email (voir .logo dans themes/default.css), et une fois en base64 le
    // fichier plein format faisait dépasser le seuil de troncature de Gmail
    // (~102 Ko de HTML), qui repliait alors tout le message sous un lien
    // "Afficher l'intégralité du message".
    $logoPath = public_path('images/Logo-email.png');
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
