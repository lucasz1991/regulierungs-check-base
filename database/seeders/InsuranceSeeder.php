<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Insurance;


class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insurances = [
            ['name' => 'HUK24', 'slug' => 'huk24', 'description' => 'Online-Versicherung', 'initials' => 'HUK', 'color' => '#0077cc'],
            ['name' => 'Allianz', 'slug' => 'allianz', 'description' => 'Großer Versicherer', 'initials' => 'AZ', 'color' => '#002d72'],
            ['name' => 'AXA', 'slug' => 'axa', 'description' => 'Internationaler Versicherer', 'initials' => 'AXA', 'color' => '#005bab'],
            ['name' => 'ERGO', 'slug' => 'ergo', 'description' => 'Versicherung für Privatkunden', 'initials' => 'ER', 'color' => '#e30613'],
            ['name' => 'R+V', 'slug' => 'rv', 'description' => 'Genossenschaftlicher Versicherer', 'initials' => 'RV', 'color' => '#007a33'],
        ];

        foreach ($insurances as $insurance) {
            Insurance::create($insurance);
        }
    }
}
