<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insurance;
use App\Models\InsuranceType;

class InsuranceInsuranceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = InsuranceType::all();
        $insurances = Insurance::all();

        if ($types->isEmpty() || $insurances->count() < 3) {
            $this->command->warn('Es müssen mindestens 3 Versicherungen und 1 Versicherungstyp existieren.');
            return;
        }

        foreach ($types as $type) {
            // Nimm 3 bis 5 zufällige Versicherungen
            $randomInsurances = $insurances->random(rand(3, min(5, $insurances->count())))->values();

            foreach ($randomInsurances as $index => $insurance) {
                $insurance->insuranceTypes()->attach($type->id, ['order_column' => $index]);
            }
        }
    }
}
