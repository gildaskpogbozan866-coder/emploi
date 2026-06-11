<?php

namespace Database\Seeders;

use App\Models\TypeDocument;
use Illuminate\Database\Seeder;

class TypeDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['nom' => 'Curriculum Vitae (CV)',             'description' => 'CV au format PDF ou Word',                          'ordre' => 1],
            ['nom' => 'Diplôme',                           'description' => 'Diplôme d\'études (licence, master, doctorat…)',     'ordre' => 2],
            ['nom' => 'Attestation de formation',          'description' => 'Attestation de fin de formation ou de stage',        'ordre' => 3],
            ['nom' => 'Certificat professionnel',          'description' => 'Certificat obtenu après une formation technique',    'ordre' => 4],
            ['nom' => 'Relevé de notes',                   'description' => 'Relevé de notes officiel d\'un établissement',      'ordre' => 5],
            ['nom' => 'Lettre de recommandation',          'description' => 'Lettre d\'un ancien employeur ou formateur',        'ordre' => 6],
            ['nom' => 'Attestation de travail',            'description' => 'Attestation délivrée par un employeur',             'ordre' => 7],
            ['nom' => 'Autre document',                    'description' => 'Tout autre document justificatif',                  'ordre' => 8],
        ];

        foreach ($types as $type) {
            TypeDocument::firstOrCreate(
                ['nom' => $type['nom']],
                array_merge($type, ['actif' => true])
            );
        }
    }
}
