<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InsuranceSubtype;

class InsuranceSubtypeSeeder extends Seeder
{
    public function run(): void
    {
        InsuranceSubtype::firstOrCreate([
            'name' => 'Krankenversicherung'
        ], [
            'slug' => 'krankenversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Lebensversicherung'
        ], [
            'slug' => 'lebensversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Unfallversicherung'
        ], [
            'slug' => 'unfallversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Berufsunfähigkeitsversicherung'
        ], [
            'slug' => 'berufsunfaehigkeitsversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Pflegeversicherung'
        ], [
            'slug' => 'pflegeversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Rentenversicherung'
        ], [
            'slug' => 'rentenversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Reiseversicherung'
        ], [
            'slug' => 'reiseversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Sterbegeldversicherung'
        ], [
            'slug' => 'sterbegeldversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Krankentagegeldversicherung'
        ], [
            'slug' => 'krankentagegeldversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Zahnzusatzversicherung'
        ], [
            'slug' => 'zahnzusatzversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Auslandskrankenversicherung'
        ], [
            'slug' => 'auslandskrankenversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Dread-Disease-Versicherung (für schwere Krankheiten)'
        ], [
            'slug' => 'dread-disease-versicherung-fuer-schwere-krankheiten',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Prämien-Rückerstattungsversicherung'
        ], [
            'slug' => 'praemien-rueckerstattungsversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Private Pflegezusatzversicherung'
        ], [
            'slug' => 'private-pflegezusatzversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Zusatzversicherungen (wie z. B. für Krankenhäuser)'
        ], [
            'slug' => 'zusatzversicherungen-wie-z-b-fuer-krankenhaeuser',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Krankenhauszusatzversicherung'
        ], [
            'slug' => 'krankenhauszusatzversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Kapitalbildende Lebensversicherung'
        ], [
            'slug' => 'kapitalbildende-lebensversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Risiko-Lebensversicherung'
        ], [
            'slug' => 'risiko-lebensversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Fondsgebundene Lebensversicherung'
        ], [
            'slug' => 'fondsgebundene-lebensversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Rückdeckungsversicherung'
        ], [
            'slug' => 'rueckdeckungsversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Risiko-Lebensversicherung für Kredite'
        ], [
            'slug' => 'risiko-lebensversicherung-fuer-kredite',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Hausratversicherung'
        ], [
            'slug' => 'hausratversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Wohngebäudeversicherung'
        ], [
            'slug' => 'wohngebaeudeversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Kfz-Versicherung'
        ], [
            'slug' => 'kfz-versicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Haftpflichtversicherung'
        ], [
            'slug' => 'haftpflichtversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Teilkasko'
        ], [
            'slug' => 'teilkasko',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Vollkasko'
        ], [
            'slug' => 'vollkasko',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Glasversicherung'
        ], [
            'slug' => 'glasversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Warentransportversicherung'
        ], [
            'slug' => 'warentransportversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Luftfrachtversicherung'
        ], [
            'slug' => 'luftfrachtversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Privatrechtsschutz'
        ], [
            'slug' => 'privatrechtsschutz',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Berufsrechtsschutz'
        ], [
            'slug' => 'berufsrechtsschutz',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Verkehrsrechtsschutz'
        ], [
            'slug' => 'verkehrsrechtsschutz',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Mietrechtsschutz'
        ], [
            'slug' => 'mietrechtsschutz',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Privathaftpflichtversicherung'
        ], [
            'slug' => 'privathaftpflichtversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Bauherrenhaftpflichtversicherung'
        ], [
            'slug' => 'bauherrenhaftpflichtversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Tierhalterhaftpflichtversicherung'
        ], [
            'slug' => 'tierhalterhaftpflichtversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Vermieterhaftpflichtversicherung'
        ], [
            'slug' => 'vermieterhaftpflichtversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Jagdhaftpflichtversicherung'
        ], [
            'slug' => 'jagdhaftpflichtversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Umwelthaftpflichtversicherung'
        ], [
            'slug' => 'umwelthaftpflichtversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Produkthaftpflichtversicherung'
        ], [
            'slug' => 'produkthaftpflichtversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Schmuckversicherung'
        ], [
            'slug' => 'schmuckversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Warenlager-Versicherung'
        ], [
            'slug' => 'warenlager-versicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Kunstversicherung'
        ], [
            'slug' => 'kunstversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Bargeldversicherung'
        ], [
            'slug' => 'bargeldversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Sammlungsversicherung (z. B. für Briefmarken, Münzen)'
        ], [
            'slug' => 'sammlungsversicherung-z-b-fuer-briefmarken-muenzen',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Photovoltaikanlagenversicherung'
        ], [
            'slug' => 'photovoltaikanlagenversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

        InsuranceSubtype::firstOrCreate([
            'name' => 'Ernteversicherung (für landwirtschaftliche Betriebe)'
        ], [
            'slug' => 'ernteversicherung-fuer-landwirtschaftliche-betriebe',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);

    }
}
