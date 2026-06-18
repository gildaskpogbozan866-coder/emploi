<?php
$alerte = App\Models\Alerte::where('active', true)->where('frequence', 'immediat')->first();
$offre  = App\Models\Offre::latest()->first();

echo 'ALERTE: ' . json_encode($alerte?->only(['metier','localisation','type_contrat','secteur','frequence']), JSON_PRETTY_PRINT) . PHP_EOL;
echo 'OFFRE:  ' . json_encode($offre?->only(['titre','metier','localisation','type','secteur']), JSON_PRETTY_PRINT) . PHP_EOL;

if ($alerte && $offre) {
    $service = new App\Services\AlerteService();
    echo 'MATCH: ' . ($service->matcheOffre($alerte, $offre) ? 'OUI' : 'NON') . PHP_EOL;
} else {
    echo 'ALERTE IMMEDIAT ou OFFRE introuvable.' . PHP_EOL;
}
