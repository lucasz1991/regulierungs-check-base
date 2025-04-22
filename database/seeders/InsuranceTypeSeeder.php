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
            ['name' => 'Kfz-Versicherung', 'slug' => 'kfz', 'description' => 'Autoversicherung'],
            ['name' => 'Hausratversicherung', 'slug' => 'hausrat', 'description' => 'Hausrat'],
            ['name' => 'Haftpflichtversicherung', 'slug' => 'haftpflicht', 'description' => 'Privathaftpflicht'],
            ['name' => 'Rechtsschutzversicherung', 'slug' => 'rechtsschutz', 'description' => 'Rechtsschutz'],
            ['name' => 'Reiseversicherung', 'slug' => 'reise', 'description' => 'Reiseversicherung'],
        ];

        foreach ($types as $type) {
            InsuranceType::create($type);
        }
    }
}
