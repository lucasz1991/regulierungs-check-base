<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insurance;
use App\Models\InsuranceType;

class InsuranceInsuranceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $insurance = Insurance::where('name', 'ADAC Autoversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ADAC Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ADAC Zuhause Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ADLER Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ADVOCARD Rechtsschutzversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'AEGIDIUS SE')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'AGER Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'AGILA Haustierversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Aioi Nissay Dowa Life Insurance of Europe Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allcura Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allgemeine Rentenanstalt Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allianz Direct Versicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allianz Global Corporate & Specialty SE')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allianz Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allianz Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allianz Pensionskasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allianz Private Krankenversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allianz SE')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allianz Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Allianz Versorgungskasse Versicherungsverein a.G.')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Alte Leipziger Lebensversicherung auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ALTE LEIPZIGER Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ALTE LEIPZIGER Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Alte Leipziger Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ALTE OLDENBURGER Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ALTE OLDENBURGER Krankenversicherung von 1927 Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Alters- und Hinterbliebenen- Versicherung der Technischen Überwachungs-Vereine-VVaG-')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Ambra Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'AMMERLÄNDER VERSICHERUNG Versicherungsverein a.G. (VVaG)')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'andsafe Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Angest.-Pensionskasse der dt. Geschäftsbetriebe der Georg Fischer Aktiengesellschaft Schaffhausen (Schweiz)')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ARAG Allgemeine Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ARAG Krankenversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ARAG SE')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Astra Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Athora Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Athora Pensionskasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Audi Pensionskasse-Altersversorgung der AUTO UNION GmbH, Versicherungsverein auf Gegenseitigk. (VVaG) Ingolst./Donau')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Augenoptiker Ausgleichskasse VVaG (AKA)')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'AUXILIA Rechtsschutz-Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'AXA easy Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'AXA Krankenversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'AXA Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'AXA Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BA die Bayerische Allgemeine Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Babcock Pensionskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Baden-Badener Pensionskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Badische Rechtsschutzversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Badischer Gemeinde-Versicherungs-Verband')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Baloise Lebensversicherung Aktiengesellschaft Deutschland')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Baloise Sachversicherung Aktiengesellschaft Deutschland')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Barmenia Allgemeine Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Barmenia Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Barmenia Versicherungen a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Barmenia.Gothaer Finanzholding Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BASF Pensionskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BASF Sterbekasse VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BavariaDirekt Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bayer Beistandskasse')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bayer-Pensionskasse')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bayerische Beamtenkrankenkasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bayerische Hausbesitzer-Versicherungs-Gesellschaft a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bayerische Landesbrandversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bayerischer Versicherungsverband Versicherungsaktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bayern-Versicherung Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BD24 Berlin Direkt Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bergische Brandversicherung Allgemeine Feuerversicherung V.a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Betriebspensionskasse der Firma Carl Schenck AG VVaG Darmstadt')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BGV-Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BL die Bayerische Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bochumer Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Bosch Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BVV Pensionsfonds des Bankgewerbes AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BVV Versicherungsverein des Bankgewerbes a.G.')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'BY die Bayerische Vorsorge Lebensversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'CG Car-Garantie Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'CHEMIE Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Concordia Krankenversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Concordia oeco Lebensversicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Concordia Versicherungs-Gesellschaft auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Condor Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'CONSTANTIA Versicherungen a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Continentale Krankenversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Continentale Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Continentale Sachversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Cosmos Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Cosmos Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Credit Life AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DA Deutsche Allgemeine Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DARAG Deutschland AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Debeka Allgemeine Versicherung Aktiengesellschaft Sitz Koblenz am Rhein')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Debeka Krankenversicherungsverein auf Gegenseitigkeit Sitz Koblenz am Rhein')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Debeka Lebensversicherungsverein auf Gegenseitigkeit Sitz Koblenz am Rhein')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Debeka Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Debeka Zusatzversorgungskasse VaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Delta Direkt Lebensversicherung Aktiengesellschaft München')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Delvag Versicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DEURAG Deutsche Rechtsschutz-Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Deutsche Assistance Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Deutsche Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Deutsche Post Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Deutsche Rückversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Deutsche Ärzteversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Deutscher Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DEVK Allgemeine Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DEVK Allgemeine Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DEVK Deutsche Eisenbahn Versicherung Lebensversicherungsverein a.G. Betriebliche Sozialeinrichtung der Deutschen Bahn')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DEVK Deutsche Eisenbahn Versicherung Sach- und HUK-Versicherungsverein a.G. Betriebliche Sozialeinrichtung der Deutschen Bahn')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DEVK Krankenversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DEVK Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DEVK Rechtsschutz-Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DEVK Rückversicherungs- und Beteiligungs-Aktiengesellschaft - DEVK RE')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DFV Deutsche Familienversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Dialog Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Dialog Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Die Haftpflichtkasse VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Diehl Assekuranz Rückversicherungs- und Vermittlungs-AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DIREKTE LEBEN Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DKV Deutsche Krankenversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DMB Rechtsschutz-Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DOCURA VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Dolleruper Freie Brandgilde')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Domestic & General Insurance Europe AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Dortmunder Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'DPK Deutsche Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Dresdener Pensionskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'E+S Rückversicherung AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'E.ON Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ELEMENT Insurance AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Entis Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ENVIVAS Krankenversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ERGO Direkt Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ERGO Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ERGO Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ERGO Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ERGO Pensionskasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ERGO Reiseversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ERGO Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ERGO Vorsorge Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Erste Kieler Beerdigungskasse')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Euro-Aviation Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'EUROPA Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'EUROPA Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'EXTREMUS Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'F. Laeisz Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Fahrlehrerversicherung Verein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Feuer- und Einbruchschadenkasse der BBBank in Karlsruhe, Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Feuerbestattungsverein VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Feuersozietät Berlin Brandenburg Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Frankfurt Münchener Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Frankfurter Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Frankfurter Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Freeyou Insurance AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Freudenberg Rückversicherung AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Fürsorgekasse von 1908 vormals Sterbekasse der Neuapostolischen Kirche des Landes Nordrhein-Westfalen')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'GARANTA Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Gartenbau-Versicherung VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'GE.BE.IN Versicherungen Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Gebäudeversicherungsgilde für Föhr,Amrum und Halligen')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Gemeinnützige Haftpflichtversicherungsanstalt Kassel')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'General Reinsurance AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Generali Deutschland AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Generali Deutschland Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Generali Deutschland Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Generali Deutschland Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Generali Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Geno Pensionskasse VVaG, Karlsruhe')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Gerling Versorgungskasse')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Getsafe Insurance AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'GGG Kraftfahrzeug-Reparaturkosten-Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Glatfelter Gernsbach Pensionskasse  VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Gothaer Allgemeine Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Gothaer Krankenversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Gothaer Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Gothaer Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Gothaer Versicherungsbank VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Great Lakes Insurance SE')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'GRUNDEIGENTÜMER-VERSICHERUNG Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Grün + Bilfinger Wohlfahrts-und Pensionskasse a.G., c/o LDIS GmbH')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'GVO Gegenseitigkeit Versicherung Oldenburg VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'GVV Direktversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'GVV-Kommunalversicherung, Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Haftpflichtgemeinschaft Deutscher Nahverkehrs- und Versorgungsunternehmen Allgemein (HDNA) VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hagelgilde Versicherungsverein a.G., gegründet 1811')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hallesche Krankenversicherung auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hamburger Beamten-Feuer-und Einbruchskasse')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hamburger Feuerkasse Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hamburger Lehrer-Feuerkasse')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HAMBURGER PENSIONSFONDS Pensionsfondsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HAMBURGER PENSIONSKASSE VON 1905 Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HAMBURGER PENSIONSRÜCKDECKUNGSKASSE  Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hannover Rück SE')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hannoversche Alterskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hannoversche Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hannoversche Pensionskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hanse-Marine-Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HanseMerkur Allgemeine Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HanseMerkur Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HanseMerkur Krankenversicherung auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HanseMerkur Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HanseMerkur Reiseversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HanseMerkur Speziale Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Harsewinkeler Versicherung VaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HDI Global Network AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HDI Global SE')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HDI Global Specialty SE')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HDI Haftpflichtverband der Deutschen Industrie Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HDI Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HDI Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HDI Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HDI Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HEAG Pensionszuschusskasse Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Heidelberger Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HELVETIA schweizerische Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Helvetia Schweizerische Versicherungsgesellschaft AG Direktion für Deutschland')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Helvetia Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hilfskasse BVG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hinterbliebenenkasse der Heilberufe HDH Versicherungsverein a.G. in München')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hochrhein Internationale Rückversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HUK-COBURG Haftpflicht-Unterstützungs-Kasse kraftfahrender Beamter Deutschlands a.G. in Coburg')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HUK-COBURG-Allgemeine Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HUK-COBURG-Holding AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HUK-COBURG-Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HUK-COBURG-Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HUK-COBURG-Rechtsschutzversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HUK24 AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'HVB Trust Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Häger Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Höchster Pensionskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Höchster Sterbekasse VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Hübener Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'IBM Deutschland Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'IBM Deutschland Pensionskasse Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'IDEAL Lebensversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'IDEAL Sterbekasse Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'IDEAL Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Incura AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'INTER Allgemeine Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'INTER Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'INTER Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'INTER Versicherungsverein aG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Interlloyd Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'InterRisk Lebensversicherungs-AG Vienna Insurance Group.')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'InterRisk Versicherungs-AG Vienna Insurance Group.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ISSELHORSTER Versicherung V.a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Itzehoer Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Itzehoer Versicherung/Brandgilde von 1691 Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Janitos Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Kieler Rückversicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Krankenunterstützungskasse Hannover')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'KRAVAG-ALLGEMEINE Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'KRAVAG-LOGISTIC Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'KRAVAG-SACH Versicherung des Deutschen Kraftverkehrs Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'KS Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'KölnVorsorge-Sterbeversicherung VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Landeskrankenhilfe V.V.a.G.')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Landesschadenhilfe Versicherung VaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Landschaftliche Brandkasse Hannover')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'LBN-Versicherungsverein a.G. (VVaG)')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Lebensversicherung von 1871 auf Gegenseitigkeit München')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Lehrer-Feuerversicherungsverein a.G. für Schleswig-Holstein, Hamburg und Mecklenburg-Vorpommern')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Lifestyle Protection AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Lifestyle Protection Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'LIGA Krankenversicherung katholischer Priester Versicherungsverein auf Gegenseitigkeit Regensburg')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Lippische Landesbrandversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Lippische Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Lohnfortzahlungskasse Leer VVaG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'LPV Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'LPV Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Lucura Versicherungs AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'LVM Krankenversicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'LVM Landwirtschaftlicher Versicherungsverein Münster a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'LVM Lebensversicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'LVM Pensionsfonds-AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Mannheimer Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Markel Insurance SE')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Mecklenburgische Krankenversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Mecklenburgische Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Mecklenburgische Versicherungs-Gesellschaft auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'MER-Pensionskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Mercedes-Benz Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Mercedes-Benz Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Mercer Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'METRO Re AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Metzler Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Metzler Sozialpartner Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Minerva Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'MSIG Insurance Europe AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'MVK Versicherung VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'myLife Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Müllerei-Pensionskasse Versicherungsverein a.G. (MPK)')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Münchener Rück Versorgungskasse')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Münchener Rückversicherungs-Gesellschaft Aktiengesellschaft in München')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'MÜNCHENER VEREIN Allgemeine Versicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'MÜNCHENER VEREIN Krankenversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'MÜNCHENER VEREIN Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Neodigital Autoversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Neodigital Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Nestlé Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'NESTLÉ PENSIONSKASSE')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'NESTLÉ RÜCKDECKUNGSKASSE')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'neue leben Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'neue leben Pensionskasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'neue leben Unfallversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Neue Rechtsschutz-Versicherungsgesellschaft Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Neuendorfer Brand-Bau-Gilde')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Newline Europe Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'nexible Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Nordhemmer Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Notarversicherungsverein a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'NV-Versicherungen VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'NÜRNBERGER Allgemeine Versicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'NÜRNBERGER BEAMTEN ALLGEMEINE VERSICHERUNG AKTIENGESELLSCHAFT')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'NÜRNBERGER Krankenversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'NÜRNBERGER Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'NÜRNBERGER Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'NÜRNBERGER Pensionskasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'OKV - Ostdeutsche Kommunalversicherung auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'OSTANGLER BRANDGILDE, Versicherungsverein auf Gegenseitigkeit (VVaG)')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'OSTBEVERNER Versicherungsverein auf Gegenseitigkeit (VVaG)')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ottonova Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pallas Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensions-Sicherungs-Verein Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse Berolina VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse Degussa Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der BERLIN-KÖLNISCHE Versicherungen')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Bewag')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der BHW Bausparkasse')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der BOGESTRA')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'PENSIONSKASSE DER CREOS UND ENOVOS DEUTSCHLAND VVAG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der EDEKA Organisation V.V.a.G.')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Frankfurter Sparkasse')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Genossenschaftsorganisation Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'PENSIONSKASSE der Hamburger Hochbahn Aktiengesellschaft - VVaG -')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der HELVETIA Schweizerische Versicherungsgesellschaft, Direktion für Deutschland')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der HypoVereinsbank')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Lotsenbrüderschaft Elbe')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Mitarbeiter der ehemaligen Frankona Rückversicherungs-AG V.V.a.G.')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Mitarbeiter der Hoechst-Gruppe VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Novartis Pharma GmbH in Nürnberg Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Rechtsanwälte und Notare VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Schülke & Mayr GmbH V.V.a.G. c/o Aon Solutions Germany GmbH')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Vereinigten Hagelversicherung VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der VHV-Versicherungen')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Wacker Chemie Versicherungsverein a.G.')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Wasserwirtschaftlichen Verbände Essen VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse der Württembergischen')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse des BDH Bundesverband Rehabilitation, VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse Deutscher Eisenbahnen und Straßenbahnen VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'PENSIONSKASSE Deutscher Genossenschaften VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse Dynamit Nobel Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse für Angestellte der Continental Aktiengesellschaft VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse für die Angestellten der BARMER Ersatzkasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse für die Arbeitnehmerinnen und Arbeitnehmer des ZDF Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse für die Deutsche Wirtschaft vormals Pensionskasse der chemischen Industrie Deutschlands')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse HT Troplast Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse Konzern Versicherungskammer Bayern VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse Maxhütte VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'PENSIONSKASSE PEUGEOT DEUTSCHLAND VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse Rundfunk')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse Schenker VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse SIGNAL Versicherungen VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pensionskasse vom Deutschen Roten Kreuz VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Philips Pensionskasse (Versicherungsverein auf Gegenseitigkeit)')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Phoenix Pensionskasse von 1925 Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'praenatura Versicherungsverein auf Gegenseitigkeit (VVaG)')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Pro bAV Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ProTect Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Protektor Lebensversicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Provinzial Holding Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Provinzial Krankenversicherung Hannover AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Provinzial Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Provinzial Lebensversicherung Hannover')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Provinzial Nord Brandkasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Provinzial Pensionskasse Hannover AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Provinzial Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Proxalto Lebensversicherung Aktiengesellschaft c/o Viridium Group GmbH & Co. KG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'PRUDENTIA Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'PVAG Polizeiversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'R + V Lebensversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'R + V LEBENSVERSICHERUNG AKTIENGESELLSCHAFT')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'R+V Allgemeine Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'R+V Direktversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'R+V Krankenversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'R+V Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'R+V PENSIONSKASSE AKTIENGESELLSCHAFT')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'R+V PENSIONSVERSICHERUNG a.G.')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'R+V VERSICHERUNG AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Real Garant Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Rentenzuschusskasse der N-ERGIE Aktiengesellschaft Nürnberg')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'REVIUM Rückversicherung AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Rheinische Pensionskasse')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'RheinLand Versicherungs Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Rhion Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'RISICOM Rückversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ROLAND Rechtsschutz-Versicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ROLAND Schutzbrief-Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Ruhegeldkasse der Bremer Straßenbahn (VVaG)')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'RWE Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'S DirektVersicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SAARLAND Feuerversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Schleswiger Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SCHNEVERDINGER Versicherungsverein a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Schutzverein Deutscher Rheder V.a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SHB Allgemeine Versicherung VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Siemens Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SIGNAL IDUNA Allgemeine Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SIGNAL IDUNA Krankenversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SIGNAL IDUNA Lebensversicherung a. G.')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SIGNAL IDUNA Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SIGNAL IDUNA Pensionskasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SIGNAL IDUNA Sterbekasse VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SIGNAL IDUNA Unfallversicherung a. G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Skandia Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SOLIDAR Versicherungsgemeinschaft Sterbegeldversicherung VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SONO Krankenversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SONO Sterbegeld VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sparkassen Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sparkassen Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sparkassen-Versicherung Sachsen Allgemeine Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sparkassen-Versicherung Sachsen Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sterbe-Unterstützungs-Vereinigung der Beschäftigten der Stadt München')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sterbekasse der Bediensteten der Stadtverwaltung Dortmund')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sterbekasse der Belegschaft der Saarstahl AG Völklingen, Werk Völklingen und Werk Burbach')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sterbekasse der Feuerwehren, Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sterbekasse der Saarbergleute VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sterbekasse Evangelischer Freikirchen VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sterbekasse für die Angestellten der Deutsche Bank Gruppe')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Sterbekasse Sozialversicherung- gegr. in der LVA Rheinprovinz - Düsseldorf')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Stuttgarter Lebensversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Stuttgarter Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SV SparkassenVersicherung Gebäudeversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SV SparkassenVersicherung Holding Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SV SparkassenVersicherung Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'SV SparkassenVersicherung Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Swiss Life Lebensversicherung SE')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Swiss Life Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Swiss Life Pensionskasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Süddeutsche Allgemeine Versicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Süddeutsche Krankenversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Süddeutsche Lebensversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Talanx Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'TARGO Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'TARGO Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Telekom-Pensionsfonds a.G.')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'TK Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'TRIAS Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Uelzener Allgemeine Versicherungs-Gesellschaft a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'UNION KRANKENVERSICHERUNG AKTIENGESELLSCHAFT')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Union Reiseversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'uniVersa Allgemeine Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'uniVersa Krankenversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'uniVersa Lebensversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VdW Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Vereinigte Hagelversicherung VVaG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VEREINIGTE POSTVERSICHERUNG VVaG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Vereinigte Schiffs-Versicherung V. a. G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Vereinigte Tierversicherung, Gesellschaft auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VERKA PK Kirchliche Pensionskasse AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VERKA VK Kirchliche Vorsorge VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versicherer im Raum der Kirchen Krankenversicherung AG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versicherer im Raum der Kirchen Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versicherer im Raum der Kirchen Sachversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versicherungskammer Bayern Konzern-Rückversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versicherungskammer Bayern Pensionskasse Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versicherungskammer Bayern Versicherungsanstalt des öffentlichen Rechts')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versicherungsverband Deutscher Eisenbahnen-Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versicherungsverein Rasselstein')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungsanstalt des Bundes und der Länder')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungsausgleichskasse Pensionskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse der Angestellten der GEA Group Aktiengesellschaft VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse der Angestellten der Norddeutschen Affinerie')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse der Arbeiter und Angestellten der ehemaligen Großkraftwerk Franken AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse der Deutscher Herold Versicherungsgesellschaften,Versicherungsverein a.G., Köln')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse der ehemaligen Bayernwerk AG, Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse der Firma M. DuMont Schauberg Expedition der Kölnischen Zeitung')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse der Volksfürsorge VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse Deutscher Unternehmen, Versicherungsverein auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse f. d. Angest. d. AachenMünchener Versicherung AG u.d. Generali Deutschland AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse Gothaer Versicherungsbank VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungskasse Radio Bremen')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Versorgungslasten-Ausgleichskasse des Genossenschaftsverbandes-Verband der Regionen e.V.- VVaG - Hannover')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Verti Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VHV Allgemeine Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VHV Vereinigte Hannoversche Versicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Victoria Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VIFA Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'vigo Krankenversicherung VVaG')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Viridium Rückversicherung AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Volkswagen Autoversicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Volkswagen Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Volkswohl-Bund Lebensversicherung a.G.')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VOLKSWOHL-BUND SACHVERSICHERUNG AKTIENGESELLSCHAFT')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Vorsorgekasse der Commerzbank Versicherungsverein a.G.')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Vorsorgekasse Hoesch Dortmund Sterbegeldversicherung VVaG')->first();
        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VPV Allgemeine Versicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VPV Lebensversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VRK Versicherungsverein auf Gegenseitigkeit im Raum der Kirchen')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'VöV Rückversicherung KöR')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Waldenburger Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'WERTGARANTIE SE')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'WGV-Lebensversicherung AG')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'WGV-Versicherung AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'WTW Pensionsfonds AG')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Wuppertaler Pensionskasse VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'WWK Allgemeine Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'WWK Lebensversicherung auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'WWK Pensionsfonds Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Württembergische Gemeinde-Versicherung auf Gegenseitigkeit')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Württembergische Krankenversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Württembergische Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Württembergische Versicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Würzburger Versicherungs-AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Wüstenrot & Württembergische AG')->first();
        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zentrales Versorgungswerk für das Dachdeckerhandwerk VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zurich Deutscher Herold Lebensversicherung Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zurich Insurance Europe AG')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zurich Life Legacy Versicherung AG (Deutschland)')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zusatzversorgungskasse der Steine- und Erden-Industrie u. des Betonsteinhandwerks VVaG Die Bayerische Pensionskasse')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zusatzversorgungskasse des Baugewerbes AG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zusatzversorgungskasse des Dachdeckerhandwerks VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zusatzversorgungskasse des Gerüstbaugewerbes VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zusatzversorgungskasse des Maler- und Lackiererhandwerks VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zusatzversorgungskasse des Steinmetz- und Steinbildhauerhandwerks VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zusatzversorgungskasse für die Beschäftigten der Deutschen Brot- und Backwarenindustrie VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zusatzversorgungskasse für die Beschäftigten des Deutschen Bäckerhandwerks VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Zusatzversorgungswerk für Arbeitnehmer in der Land- und Forstwirtschaft - ZLF VVaG')->first();
        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Öffentliche Feuerversicherung Sachsen-Anhalt')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'Öffentliche Lebensversicherung Sachsen-Anhalt')->first();
        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
        $insurance = Insurance::where('name', 'ÖRAG Rechtsschutzversicherungs-Aktiengesellschaft')->first();
        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($insurance && $type) {
            $insurance->insuranceTypes()->syncWithoutDetaching([$type->id => ['order_column' => 0]]);
        }
    }
}
