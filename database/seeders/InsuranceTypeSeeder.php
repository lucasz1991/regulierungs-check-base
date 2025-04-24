<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsuranceType;


class InsuranceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Schaden- und Unfallversicherer unter Bundesaufsicht', 'slug' => 'schaden-und-unfallversicherer-unter-bundesaufsicht', 'description' => ''],
            ['name' => 'Rückversicherer unter Bundesaufsicht', 'slug' => 'rueckversicherer-unter-bundesaufsicht', 'description' => ''],
            ['name' => 'Lebensversicherer unter Bundesaufsicht', 'slug' => 'lebensversicherer-unter-bundesaufsicht', 'description' => ''],
            ['name' => 'Krankenversicherer unter Bundesaufsicht', 'slug' => 'krankenversicherer-unter-bundesaufsicht', 'description' => ''],
            ['name' => 'Pensionskassen unter Bundesaufsicht', 'slug' => 'pensionskassen-unter-bundesaufsicht', 'description' => ''],
            ['name' => 'Pensionsfonds unter Bundesaufsicht', 'slug' => 'pensionsfonds-unter-bundesaufsicht', 'description' => ''],
            ['name' => 'Sterbekassen unter Bundesaufsicht', 'slug' => 'sterbekassen-unter-bundesaufsicht', 'description' => ''],
            ['name' => 'Kleine Versicherungsvereine auf Gegenseitigkeit unter Bundesaufsicht', 'slug' => 'kleine-versicherungsvereine-auf-gegenseitigkeit-unter-bundesaufsicht', 'description' => ''],
            ['name' => 'Spezialversicherer unter Bundesaufsicht', 'slug' => 'spezialversicherer-unter-bundesaufsicht', 'description' => ''],
            ['name' => 'Unternehmen in Abwicklung unter Bundesaufsicht', 'slug' => 'unternehmen-in-abwicklung-unter-bundesaufsicht', 'description' => ''],
        ];

        foreach ($types as $type) {
            InsuranceType::create($type);
        }
    }
}
