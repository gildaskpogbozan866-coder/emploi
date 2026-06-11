<?php

namespace Database\Seeders;

use App\Models\SecteurActivite;
use Illuminate\Database\Seeder;

class SecteursActiviteSeeder extends Seeder
{
    public function run(): void
    {
        $secteurs = [
            ['code' => 'INFORMATIQUE',   'libelle' => 'Informatique / Numérique / Tech'],
            ['code' => 'FINANCE',        'libelle' => 'Finance / Banque / Assurance'],
            ['code' => 'SANTE',          'libelle' => 'Santé / Médical / Pharmacie'],
            ['code' => 'EDUCATION',      'libelle' => 'Éducation / Formation / Enseignement'],
            ['code' => 'COMMERCE',       'libelle' => 'Commerce / Distribution / Ventes'],
            ['code' => 'MARKETING',      'libelle' => 'Marketing / Communication / Publicité'],
            ['code' => 'RH',             'libelle' => 'Ressources Humaines'],
            ['code' => 'BTP',            'libelle' => 'BTP / Génie civil / Architecture'],
            ['code' => 'AGRICULTURE',    'libelle' => 'Agriculture / Agroalimentaire / Élevage'],
            ['code' => 'TRANSPORT',      'libelle' => 'Transport / Logistique / Supply Chain'],
            ['code' => 'ENERGIE',        'libelle' => 'Énergie / Mines / Pétrole / Environnement'],
            ['code' => 'TOURISME',       'libelle' => 'Tourisme / Hôtellerie / Restauration'],
            ['code' => 'MEDIA',          'libelle' => 'Médias / Presse / Audiovisuel / Édition'],
            ['code' => 'JURIDIQUE',      'libelle' => 'Juridique / Droit / Notariat'],
            ['code' => 'ONG',            'libelle' => 'ONG / Humanitaire / Coopération internationale'],
            ['code' => 'INDUSTRIE',      'libelle' => 'Industrie / Manufacture / Production'],
            ['code' => 'TELECOM',        'libelle' => 'Télécommunications'],
            ['code' => 'IMMOBILIER',     'libelle' => 'Immobilier / Foncier / Promotion'],
            ['code' => 'SECURITE',       'libelle' => 'Sécurité / Défense / Gardiennage'],
            ['code' => 'ADMINISTRATION', 'libelle' => 'Administration publique / Gouvernement'],
            ['code' => 'RECHERCHE',      'libelle' => 'Recherche / Développement / Innovation'],
            ['code' => 'SPORT',          'libelle' => 'Sport / Loisirs / Culture / Événementiel'],
            ['code' => 'ARTISANAT',      'libelle' => 'Artisanat / Métiers manuels / Arts'],
        ];

        foreach ($secteurs as $secteur) {
            SecteurActivite::firstOrCreate(
                ['code' => $secteur['code']],
                ['libelle' => $secteur['libelle']]
            );
        }
    }
}
