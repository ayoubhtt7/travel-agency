<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            // ── Algérie ───────────────────────────────────────────────────
            ['code' => 'ALG', 'name' => 'Aéroport Houari Boumediene',          'city' => 'Alger',        'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'ORN', 'name' => 'Aéroport Ahmed Ben Bella',            'city' => 'Oran',         'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'CZL', 'name' => 'Aéroport Mohamed Boudiaf',            'city' => 'Constantine',  'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'AAE', 'name' => 'Aéroport Rabah Bitat',                'city' => 'Annaba',       'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'BJA', 'name' => 'Aéroport Soummam Abane Ramdane',      'city' => 'Béjaïa',       'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'TLM', 'name' => 'Aéroport Zenata Messali El Hadj',     'city' => 'Tlemcen',      'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'SKI', 'name' => 'Aéroport El Bez',                     'city' => 'Sétif',        'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'QSF', 'name' => 'Aéroport Ain Arnat',                  'city' => 'Sétif',        'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'BSK', 'name' => 'Aéroport Mohamed Khider',             'city' => 'Biskra',       'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'TMR', 'name' => 'Aéroport Aguenar Hadj Bey Akhamok',   'city' => 'Tamanrasset',  'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'GJL', 'name' => 'Aéroport Ferhat Abbas',               'city' => 'Jijel',        'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'MZW', 'name' => 'Aéroport Mécheria',                   'city' => 'Mécheria',     'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'ELU', 'name' => 'Aéroport Guemar',                     'city' => 'El Oued',      'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'OGX', 'name' => 'Aéroport Ain el Beida',               'city' => 'Ouargla',      'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'GHA', 'name' => 'Aéroport Noumérat',                   'city' => 'Ghardaïa',     'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'TGR', 'name' => 'Aéroport Sidi Mahdi',                 'city' => 'Touggourt',    'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'INZ', 'name' => 'Aéroport In Salah',                   'city' => 'In Salah',     'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'BMW', 'name' => 'Aéroport Bordj Mokhtar',              'city' => 'Bordj Mokhtar','country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'IAM', 'name' => 'Aéroport In Aménas',                  'city' => 'In Aménas',    'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'HME', 'name' => 'Aéroport Hassi Messaoud',             'city' => 'Hassi Messaoud','country'=> 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'CBH', 'name' => 'Aéroport Béchar Boudghene',           'city' => 'Béchar',       'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'MUW', 'name' => 'Aéroport Ghriss',                     'city' => 'Mascara',      'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'TEE', 'name' => 'Aéroport Cheikh Larbi Tébessi',       'city' => 'Tébessa',      'country' => 'Algérie', 'country_code' => 'DZ'],
            ['code' => 'TIN', 'name' => 'Aéroport Tindouf',                    'city' => 'Tindouf',      'country' => 'Algérie', 'country_code' => 'DZ'],

            // ── France ────────────────────────────────────────────────────
            ['code' => 'CDG', 'name' => 'Aéroport Charles de Gaulle',          'city' => 'Paris',        'country' => 'France',  'country_code' => 'FR'],
            ['code' => 'ORY', 'name' => 'Aéroport d\'Orly',                    'city' => 'Paris',        'country' => 'France',  'country_code' => 'FR'],
            ['code' => 'MRS', 'name' => 'Aéroport Marseille Provence',         'city' => 'Marseille',    'country' => 'France',  'country_code' => 'FR'],
            ['code' => 'LYS', 'name' => 'Aéroport Lyon Saint-Exupéry',        'city' => 'Lyon',         'country' => 'France',  'country_code' => 'FR'],
            ['code' => 'NCE', 'name' => 'Aéroport Nice Côte d\'Azur',         'city' => 'Nice',         'country' => 'France',  'country_code' => 'FR'],

            // ── Espagne ───────────────────────────────────────────────────
            ['code' => 'BCN', 'name' => 'Aéroport El Prat',                    'city' => 'Barcelone',    'country' => 'Espagne', 'country_code' => 'ES'],
            ['code' => 'MAD', 'name' => 'Aéroport Adolfo Suárez Barajas',      'city' => 'Madrid',       'country' => 'Espagne', 'country_code' => 'ES'],

            // ── Turquie ───────────────────────────────────────────────────
            ['code' => 'IST', 'name' => 'Aéroport Istanbul',                   'city' => 'Istanbul',     'country' => 'Turquie', 'country_code' => 'TR'],
            ['code' => 'SAW', 'name' => 'Aéroport Sabiha Gökçen',              'city' => 'Istanbul',     'country' => 'Turquie', 'country_code' => 'TR'],
            ['code' => 'AYT', 'name' => 'Aéroport Antalya',                    'city' => 'Antalya',      'country' => 'Turquie', 'country_code' => 'TR'],

            // ── Émirats ───────────────────────────────────────────────────
            ['code' => 'DXB', 'name' => 'Aéroport International de Dubaï',     'city' => 'Dubaï',        'country' => 'Émirats', 'country_code' => 'AE'],
            ['code' => 'AUH', 'name' => 'Aéroport International d\'Abu Dhabi', 'city' => 'Abu Dhabi',    'country' => 'Émirats', 'country_code' => 'AE'],

            // ── Tunisie ───────────────────────────────────────────────────
            ['code' => 'TUN', 'name' => 'Aéroport Tunis-Carthage',             'city' => 'Tunis',        'country' => 'Tunisie', 'country_code' => 'TN'],
            ['code' => 'MIR', 'name' => 'Aéroport Monastir Habib Bourguiba',   'city' => 'Monastir',     'country' => 'Tunisie', 'country_code' => 'TN'],
            ['code' => 'DJE', 'name' => 'Aéroport Djerba-Zarzis',              'city' => 'Djerba',       'country' => 'Tunisie', 'country_code' => 'TN'],

            // ── Autres ────────────────────────────────────────────────────
            ['code' => 'LHR', 'name' => 'Aéroport de Heathrow',                'city' => 'Londres',      'country' => 'R.-Uni',  'country_code' => 'GB'],
            ['code' => 'FCO', 'name' => 'Aéroport Leonardo da Vinci',          'city' => 'Rome',         'country' => 'Italie',  'country_code' => 'IT'],
            ['code' => 'PRG', 'name' => 'Aéroport Václav Havel',               'city' => 'Prague',       'country' => 'Tchéquie','country_code' => 'CZ'],
            ['code' => 'CAI', 'name' => 'Aéroport International du Caire',     'city' => 'Le Caire',     'country' => 'Égypte',  'country_code' => 'EG'],
            ['code' => 'AMM', 'name' => 'Aéroport Queen Alia',                 'city' => 'Amman',        'country' => 'Jordanie','country_code' => 'JO'],
            ['code' => 'DOH', 'name' => 'Aéroport International Hamad',        'city' => 'Doha',         'country' => 'Qatar',   'country_code' => 'QA'],
            ['code' => 'KUL', 'name' => 'Aéroport International de Kuala Lumpur','city'=> 'Kuala Lumpur','country' => 'Malaisie','country_code' => 'MY'],
        ];

        DB::table('airports')->insert($airports);
    }
}
