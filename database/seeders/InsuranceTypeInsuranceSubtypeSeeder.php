<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InsuranceType;
use App\Models\InsuranceSubtype;

class InsuranceTypeInsuranceSubtypeSeeder extends Seeder
{
    public function run(): void
    {

        $type = InsuranceType::where('name', 'Schaden- und Unfallversicherer unter Bundesaufsicht')->first();
        if ($type) {
            $subtype = InsuranceSubtype::where('name', 'Haftpflicht für Landfahrzeuge mit eigenem Antrieb - c) sonstige')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 1],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Haftpflicht für Landfahrzeuge mit eigenem Antrieb - b) Haftpflicht aus Landtransporten')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 2],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Beistandsleistungen zugunsten von Personen, die sich in Schwierigkeiten befinden  - b) unter anderen Bedingungen, sofern die Risiken nicht unter andere Versicherungssparten fallen')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 3],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Feuer und Elementarschäden - f) Bodensenkungen und Erdrutsch')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 4],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Rechtsschutz')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 5],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Verschiedene finanzielle Verluste - j) nichtkommerzielle Geldverluste')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 6],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Verschiedene finanzielle Verluste - i) indirekte kommerzielle Verluste außer den bereits erwähnten')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 7],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Unfall - c) kombinierte Leistungen')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 8],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Fondsgebundene Lebensversicherung')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 9],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Allgemeine Haftpflicht')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 10],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Haftpflicht für Landfahrzeuge mit eigenem Antrieb - a) Kraftfahrzeughaftpflicht')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 11],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Verschiedene finanzielle Verluste - k) sonstige finanzielle Verluste')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 12],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Unfall - x) Unfallversicherung mit Prämienrückgewähr')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 13],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Pensionsfondsgeschäfte')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 14],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Verschiedene finanzielle Verluste - h) Miet- oder Einkommensausfall')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 15],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Hagel-, Frost- und sonstige Sachschäden')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 16],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Verschiedene finanzielle Verluste - f) unvorhergesehene Geschäftsunkosten')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 17],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Luftfahrzeughaftpflicht')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 18],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Krankheit - b) Kostenversicherung')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 19],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Krankheit - a) Tagegeld')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 20],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Transportgüter')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 21],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Schienenfahrzeug-Kasko')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 22],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Beistandsleistungen zugunsten von Personen, die sich in Schwierigkeiten befinden  - a) auf Reisen oder während der Abwesenheit von ihrem Wohnsitz oder ständigem Aufenthaltsort')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 23],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Verschiedene finanzielle Verluste - e) laufende Unkosten allgemeiner Art')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 24],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Feuer und Elementarschäden - e) Kernenergie')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 25],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Feuer und Elementarschäden - d) andere Elementarschäden außer Sturm')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 26],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'See-, Binnensee- und Flußschiffahrts-Kasko - c) Seeschiffe')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 27],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'See-, Binnensee- und Flußschiffahrtshaftpflicht')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 28],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Unfall - b) Kostenversicherung')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 29],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Landfahrzeug-Kasko (ohne Schienenfahrzeuge) - b) Landfahrzeuge ohne eigenen Antrieb')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 30],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Kaution')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 31],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Kredit - d) Hypothekendarlehen')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 32],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Verschiedene finanzielle Verluste - b) ungenügende Einkommen (allgemein)')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 33],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'See-, Binnensee- und Flußschiffahrts-Kasko - b) Binnenseeschiffe')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 34],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Unfall - d) Personenbeförderung')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 35],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Feuer und Elementarschäden - c) Sturm')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 36],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Landfahrzeug-Kasko (ohne Schienenfahrzeuge) - a) Kraftfahrzeuge')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 37],
                ]);
            }
        }

        $type = InsuranceType::where('name', 'Rückversicherer unter Bundesaufsicht')->first();
        if ($type) {
            $subtype = InsuranceSubtype::where('name', 'Nichtlebens-Rückversicherung')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 1],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Lebens-Rückversicherung')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 2],
                ]);
            }
        }

        $type = InsuranceType::where('name', 'Lebensversicherer unter Bundesaufsicht')->first();
        if ($type) {
            $subtype = InsuranceSubtype::where('name', 'Geschäfte der Verwaltung von Versorgungseinrichtungen')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 1],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Zulassung als Lebensversicherer')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 2],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Heirats- und Geburtenversicherung')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 3],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Kapitalisierungsgeschäfte')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 4],
                ]);
            }
        }

        $type = InsuranceType::where('name', 'Pensionskasse unter Bundesaufsicht')->first();
        if ($type) {
            $subtype = InsuranceSubtype::where('name', 'Fondsgebundene Lebensversicherung')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 1],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Zulassung als Lebensversicherer')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 2],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Leben')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 3],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Geschäfte der Verwaltung von Versorgungseinrichtungen')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 4],
                ]);
            }
        }

        $type = InsuranceType::where('name', 'Pensionsfonds unter Bundesaufsicht')->first();
        if ($type) {
            $subtype = InsuranceSubtype::where('name', 'Pensionsfondsgeschäfte')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 1],
                ]);
            }
        }

        $type = InsuranceType::where('name', 'Krankenversicherer unter Bundesaufsicht')->first();
        if ($type) {
            $subtype = InsuranceSubtype::where('name', 'Krankheit - b) Kostenversicherung')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 1],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Krankheit - c) kombinierte Leistungen')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 2],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Krankheit - a) Tagegeld')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 3],
                ]);
            }
        }

        $type = InsuranceType::where('name', 'Sterbekasse unter Bundesaufsicht')->first();
        if ($type) {
            $subtype = InsuranceSubtype::where('name', 'Leben')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 1],
                ]);
            }
            $subtype = InsuranceSubtype::where('name', 'Zulassung als Sterbekasse')->first();
            if ($subtype) {
                $type->subtypes()->sync([
                    $subtype->id => ['order_id' => 2],
                ]);
            }
        }

    }
}
