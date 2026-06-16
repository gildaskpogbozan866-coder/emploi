<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regions')->truncate();

        $communes = [
            // ── Littoral ─────────────────────────────────────────────────
            ['nom' => 'Cotonou',           'code' => 'LT-COT', 'ordre' => 101],

            // ── Atlantique ───────────────────────────────────────────────
            ['nom' => 'Abomey-Calavi',     'code' => 'AT-ACA', 'ordre' => 201],
            ['nom' => 'Allada',            'code' => 'AT-ALL', 'ordre' => 202],
            ['nom' => 'Kpomassè',          'code' => 'AT-KPO', 'ordre' => 203],
            ['nom' => 'Ouidah',            'code' => 'AT-OUI', 'ordre' => 204],
            ['nom' => 'Sô-Ava',            'code' => 'AT-SOA', 'ordre' => 205],
            ['nom' => 'Toffo',             'code' => 'AT-TOF', 'ordre' => 206],
            ['nom' => 'Tori-Bossito',      'code' => 'AT-TOR', 'ordre' => 207],
            ['nom' => 'Zè',                'code' => 'AT-ZE',  'ordre' => 208],

            // ── Ouémé ────────────────────────────────────────────────────
            ['nom' => 'Porto-Novo',        'code' => 'OU-PON', 'ordre' => 301],
            ['nom' => 'Adjarra',           'code' => 'OU-ADJ', 'ordre' => 302],
            ['nom' => 'Adjohoun',          'code' => 'OU-ADH', 'ordre' => 303],
            ['nom' => 'Aguégués',          'code' => 'OU-AGU', 'ordre' => 304],
            ['nom' => 'Akpro-Missérété',   'code' => 'OU-AKP', 'ordre' => 305],
            ['nom' => 'Avrankou',          'code' => 'OU-AVR', 'ordre' => 306],
            ['nom' => 'Bonou',             'code' => 'OU-BON', 'ordre' => 307],
            ['nom' => 'Dangbo',            'code' => 'OU-DAN', 'ordre' => 308],
            ['nom' => 'Sèmè-Kpodji',      'code' => 'OU-SKP', 'ordre' => 309],

            // ── Plateau ──────────────────────────────────────────────────
            ['nom' => 'Adja-Ouèrè',        'code' => 'PL-ADO', 'ordre' => 401],
            ['nom' => 'Ifangni',           'code' => 'PL-IFA', 'ordre' => 402],
            ['nom' => 'Kétou',             'code' => 'PL-KET', 'ordre' => 403],
            ['nom' => 'Pobè',              'code' => 'PL-POB', 'ordre' => 404],
            ['nom' => 'Sakété',            'code' => 'PL-SAK', 'ordre' => 405],

            // ── Zou ──────────────────────────────────────────────────────
            ['nom' => 'Abomey',            'code' => 'ZO-ABO', 'ordre' => 501],
            ['nom' => 'Agbangnizoun',      'code' => 'ZO-AGB', 'ordre' => 502],
            ['nom' => 'Bohicon',           'code' => 'ZO-BOH', 'ordre' => 503],
            ['nom' => 'Covè',              'code' => 'ZO-COV', 'ordre' => 504],
            ['nom' => 'Djidja',            'code' => 'ZO-DJI', 'ordre' => 505],
            ['nom' => 'Ouinhi',            'code' => 'ZO-OUI', 'ordre' => 506],
            ['nom' => 'Za-Kpota',          'code' => 'ZO-ZAK', 'ordre' => 507],
            ['nom' => 'Zangnanado',        'code' => 'ZO-ZAN', 'ordre' => 508],
            ['nom' => 'Zogbodomey',        'code' => 'ZO-ZOG', 'ordre' => 509],

            // ── Collines ─────────────────────────────────────────────────
            ['nom' => 'Bantè',             'code' => 'CO-BAN', 'ordre' => 601],
            ['nom' => 'Dassa-Zoumè',       'code' => 'CO-DAS', 'ordre' => 602],
            ['nom' => 'Glazoué',           'code' => 'CO-GLA', 'ordre' => 603],
            ['nom' => 'Ouèssè',            'code' => 'CO-OUE', 'ordre' => 604],
            ['nom' => 'Savalou',           'code' => 'CO-SAV', 'ordre' => 605],
            ['nom' => 'Savè',              'code' => 'CO-SVE', 'ordre' => 606],

            // ── Borgou ───────────────────────────────────────────────────
            ['nom' => 'Parakou',           'code' => 'BO-PAR', 'ordre' => 701],
            ['nom' => 'Bembèrèkè',         'code' => 'BO-BEM', 'ordre' => 702],
            ['nom' => 'Kalalé',            'code' => 'BO-KAL', 'ordre' => 703],
            ['nom' => 'N\'Dali',           'code' => 'BO-NDA', 'ordre' => 704],
            ['nom' => 'Nikki',             'code' => 'BO-NIK', 'ordre' => 705],
            ['nom' => 'Pèrèrè',            'code' => 'BO-PER', 'ordre' => 706],
            ['nom' => 'Sinendé',           'code' => 'BO-SIN', 'ordre' => 707],
            ['nom' => 'Tchaourou',         'code' => 'BO-TCH', 'ordre' => 708],

            // ── Alibori ──────────────────────────────────────────────────
            ['nom' => 'Banikoara',         'code' => 'AL-BAN', 'ordre' => 801],
            ['nom' => 'Gogounou',          'code' => 'AL-GOG', 'ordre' => 802],
            ['nom' => 'Kandi',             'code' => 'AL-KAN', 'ordre' => 803],
            ['nom' => 'Karimama',          'code' => 'AL-KAR', 'ordre' => 804],
            ['nom' => 'Malanville',        'code' => 'AL-MAL', 'ordre' => 805],
            ['nom' => 'Ségbana',           'code' => 'AL-SEG', 'ordre' => 806],

            // ── Atacora ──────────────────────────────────────────────────
            ['nom' => 'Boukoumbé',         'code' => 'AK-BOU', 'ordre' => 901],
            ['nom' => 'Cobly',             'code' => 'AK-COB', 'ordre' => 902],
            ['nom' => 'Kérou',             'code' => 'AK-KER', 'ordre' => 903],
            ['nom' => 'Kouandé',           'code' => 'AK-KOU', 'ordre' => 904],
            ['nom' => 'Matéri',            'code' => 'AK-MAT', 'ordre' => 905],
            ['nom' => 'Natitingou',        'code' => 'AK-NAT', 'ordre' => 906],
            ['nom' => 'Pehunco',           'code' => 'AK-PEH', 'ordre' => 907],
            ['nom' => 'Tanguiéta',         'code' => 'AK-TAN', 'ordre' => 908],
            ['nom' => 'Toucountouna',      'code' => 'AK-TOU', 'ordre' => 909],

            // ── Donga ────────────────────────────────────────────────────
            ['nom' => 'Bassila',           'code' => 'DO-BAS', 'ordre' => 1001],
            ['nom' => 'Copargo',           'code' => 'DO-COP', 'ordre' => 1002],
            ['nom' => 'Djougou',           'code' => 'DO-DJO', 'ordre' => 1003],
            ['nom' => 'Ouaké',             'code' => 'DO-OUA', 'ordre' => 1004],

            // ── Mono ─────────────────────────────────────────────────────
            ['nom' => 'Athiémé',           'code' => 'MO-ATH', 'ordre' => 1101],
            ['nom' => 'Bopa',              'code' => 'MO-BOP', 'ordre' => 1102],
            ['nom' => 'Comè',              'code' => 'MO-COM', 'ordre' => 1103],
            ['nom' => 'Grand-Popo',        'code' => 'MO-GRP', 'ordre' => 1104],
            ['nom' => 'Houéyogbé',         'code' => 'MO-HOU', 'ordre' => 1105],
            ['nom' => 'Lokossa',           'code' => 'MO-LOK', 'ordre' => 1106],

            // ── Couffo ───────────────────────────────────────────────────
            ['nom' => 'Aplahoué',          'code' => 'CF-APL', 'ordre' => 1201],
            ['nom' => 'Djakotomey',        'code' => 'CF-DJA', 'ordre' => 1202],
            ['nom' => 'Dogbo',             'code' => 'CF-DOG', 'ordre' => 1203],
            ['nom' => 'Klouékanmè',        'code' => 'CF-KLO', 'ordre' => 1204],
            ['nom' => 'Lalo',              'code' => 'CF-LAL', 'ordre' => 1205],
            ['nom' => 'Toviklin',          'code' => 'CF-TOV', 'ordre' => 1206],
        ];

        foreach ($communes as $c) {
            Region::create([
                'nom'   => $c['nom'],
                'code'  => $c['code'],
                'pays'  => 'Bénin',
                'ordre' => $c['ordre'],
                'actif' => true,
            ]);
        }
    }
}
