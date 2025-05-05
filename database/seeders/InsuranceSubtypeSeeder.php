<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InsuranceSubtype;

class InsuranceSubtypeSeeder extends Seeder
{
    public function run(): void
    {
        InsuranceSubtype::firstOrCreate([
            'name' => 'Haftpflicht für Landfahrzeuge mit eigenem Antrieb - c) sonstige'
        ], [
            
            'slug' => 'haftpflicht-fuer-landfahrzeuge-mit-eigenem-antrieb---c-sonstige',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Haftpflicht für Landfahrzeuge mit eigenem Antrieb - b) Haftpflicht aus Landtransporten'
        ], [
            
            'slug' => 'haftpflicht-fuer-landfahrzeuge-mit-eigenem-antrieb---b-haftpflicht-aus-landtransporten',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]); 
        InsuranceSubtype::firstOrCreate([
            'name' => 'Haftpflicht für Landfahrzeuge mit eigenem Antrieb - a) Kraftfahrzeughaftpflicht'
        ], [
            
            'slug' => 'haftpflicht-fuer-landfahrzeuge-mit-eigenem-antrieb---a-kraftfahrzeughaftpflicht',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Hagel-, Frost- und sonstige Sachschäden'
        ], [
            
            'slug' => 'hagel--frost--und-sonstige-sachschaeden',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Feuer und Elementarschäden - c) Sturm'
        ], [
            
            'slug' => 'feuer-und-elementarschaeden---c-sturm',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Feuer und Elementarschäden - b) Explosion'
        ], [
            
            'slug' => 'feuer-und-elementarschaeden---b-explosion',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Feuer und Elementarschäden - a) Feuer'
        ], [
            
            'slug' => 'feuer-und-elementarschaeden---a-feuer',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Landfahrzeug-Kasko (ohne Schienenfahrzeuge) - b) Landfahrzeuge ohne eigenen Antrieb'
        ], [
            
            'slug' => 'landfahrzeug-kasko-ohne-schienenfahrzeuge---b-landfahrzeuge-ohne-eigenen-antrieb',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Landfahrzeug-Kasko (ohne Schienenfahrzeuge) - a) Kraftfahrzeuge'
        ], [
            
            'slug' => 'landfahrzeug-kasko-ohne-schienenfahrzeuge---a-kraftfahrzeuge',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Zulassung als Schaden-/Unfallversicherung'
        ], [
            
            'slug' => 'zulassung-als-schaden-/unfallversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Feuer und Elementarschäden - d) andere Elementarschäden außer Sturm'
        ], [
            
            'slug' => 'feuer-und-elementarschaeden---d-andere-elementarschaeden-ausser-sturm',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Rechtsschutz'
        ], [
            
            'slug' => 'rechtsschutz',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Beistandsleistungen zugunsten von Personen, die sich in Schwierigkeiten befinden  - b) unter anderen Bedingungen, sofern die Risiken nicht unter andere Versicherungssparten fallen'
        ], [
            
            'slug' => 'beistandsleistungen-zugunsten-von-personen-die-sich-in-schwierigkeiten-befinden----b-unter-anderen-bedingungen-sofern-die-risiken-nicht-unter-andere-versicherungssparten-fallen',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Beistandsleistungen zugunsten von Personen, die sich in Schwierigkeiten befinden  - a) auf Reisen oder während der Abwesenheit von ihrem Wohnsitz oder ständigem Aufenthaltsort'
        ], [
            
            'slug' => 'beistandsleistungen-zugunsten-von-personen-die-sich-in-schwierigkeiten-befinden----a-auf-reisen-oder-waehrend-der-abwesenheit-von-ihrem-wohnsitz-oder-staendigem-aufenthaltsort',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - j) nichtkommerzielle Geldverluste'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---j-nichtkommerzielle-geldverluste',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Kaution'
        ], [
            
            'slug' => 'kaution',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Allgemeine Haftpflicht'
        ], [
            
            'slug' => 'allgemeine-haftpflicht',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Krankheit - b) Kostenversicherung'
        ], [
            
            'slug' => 'krankheit---b-kostenversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Krankheit - a) Tagegeld'
        ], [
            
            'slug' => 'krankheit---a-tagegeld',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Unfall - d) Personenbeförderung'
        ], [
            
            'slug' => 'unfall---d-personenbefoerderung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Unfall - c) kombinierte Leistungen'
        ], [
            
            'slug' => 'unfall---c-kombinierte-leistungen',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Unfall - b) Kostenversicherung'
        ], [
            
            'slug' => 'unfall---b-kostenversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Unfall - a) Summenversicherung'
        ], [
            
            'slug' => 'unfall---a-summenversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Feuer und Elementarschäden - f) Bodensenkungen und Erdrutsch'
        ], [
            
            'slug' => 'feuer-und-elementarschaeden---f-bodensenkungen-und-erdrutsch',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - h) Miet- oder Einkommensausfall'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---h-miet--oder-einkommensausfall',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - e) laufende Unkosten allgemeiner Art'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---e-laufende-unkosten-allgemeiner-art',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - d) Gewinnausfall'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---d-gewinnausfall',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Luftfahrzeughaftpflicht'
        ], [
            
            'slug' => 'luftfahrzeughaftpflicht',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Luftfahrzeug-Kasko'
        ], [
            
            'slug' => 'luftfahrzeug-kasko',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Nichtlebens-Rückversicherung'
        ], [
            
            'slug' => 'nichtlebens-rueckversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Zulassung als Rückversicherung'
        ], [
            
            'slug' => 'zulassung-als-rueckversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Geschäfte der Verwaltung von Versorgungseinrichtungen'
        ], [
            
            'slug' => 'geschaefte-der-verwaltung-von-versorgungseinrichtungen',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Kapitalisierungsgeschäfte'
        ], [
            
            'slug' => 'kapitalisierungsgeschaefte',
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
            'name' => 'Heirats- und Geburtenversicherung'
        ], [
            
            'slug' => 'heirats--und-geburtenversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Leben'
        ], [
            
            'slug' => 'leben',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Zulassung als Lebensversicherer'
        ], [
            
            'slug' => 'zulassung-als-lebensversicherer',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - f) unvorhergesehene Geschäftsunkosten'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---f-unvorhergesehene-geschaeftsunkosten',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - i) indirekte kommerzielle Verluste außer den bereits erwähnten'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---i-indirekte-kommerzielle-verluste-ausser-den-bereits-erwaehnten',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Transportgüter'
        ], [
            
            'slug' => 'transportgueter',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - k) sonstige finanzielle Verluste'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---k-sonstige-finanzielle-verluste',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - g) Wertverluste'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---g-wertverluste',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - c) Schlechtwetter'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---c-schlechtwetter',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - b) ungenügende Einkommen (allgemein)'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---b-ungenuegende-einkommen-allgemein',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Verschiedene finanzielle Verluste - a) Berufsrisiken'
        ], [
            
            'slug' => 'verschiedene-finanzielle-verluste---a-berufsrisiken',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Kredit - e) landwirtschaftliche Darlehen'
        ], [
            
            'slug' => 'kredit---e-landwirtschaftliche-darlehen',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Kredit - d) Hypothekendarlehen'
        ], [
            
            'slug' => 'kredit---d-hypothekendarlehen',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Kredit - c) Abzahlungsgeschäfte'
        ], [
            
            'slug' => 'kredit---c-abzahlungsgeschaefte',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Kredit - b) Ausfuhrkredit'
        ], [
            
            'slug' => 'kredit---b-ausfuhrkredit',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Kredit - a) allgemeine Zahlungsunfähigkeit'
        ], [
            
            'slug' => 'kredit---a-allgemeine-zahlungsunfaehigkeit',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'See-, Binnensee- und Flußschiffahrtshaftpflicht'
        ], [
            
            'slug' => 'see--binnensee--und-flussschiffahrtshaftpflicht',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Feuer und Elementarschäden - e) Kernenergie'
        ], [
            
            'slug' => 'feuer-und-elementarschaeden---e-kernenergie',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'See-, Binnensee- und Flußschiffahrts-Kasko - c) Seeschiffe'
        ], [
            
            'slug' => 'see--binnensee--und-flussschiffahrts-kasko---c-seeschiffe',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'See-, Binnensee- und Flußschiffahrts-Kasko - b) Binnenseeschiffe'
        ], [
            
            'slug' => 'see--binnensee--und-flussschiffahrts-kasko---b-binnenseeschiffe',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'See-, Binnensee- und Flußschiffahrts-Kasko - a) Flußschiffe'
        ], [
            
            'slug' => 'see--binnensee--und-flussschiffahrts-kasko---a-flussschiffe',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Schienenfahrzeug-Kasko'
        ], [
            
            'slug' => 'schienenfahrzeug-kasko',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Krankheit - c) kombinierte Leistungen'
        ], [
            
            'slug' => 'krankheit---c-kombinierte-leistungen',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Pensionsfondsgeschäfte'
        ], [
            
            'slug' => 'pensionsfondsgeschaefte',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Zulassung als Pensionsfond'
        ], [
            
            'slug' => 'zulassung-als-pensionsfond',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Zulassung als Krankenversicherung'
        ], [
            
            'slug' => 'zulassung-als-krankenversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Lebens-Rückversicherung'
        ], [
            
            'slug' => 'lebens-rueckversicherung',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Unfall - x) Unfallversicherung mit Prämienrückgewähr'
        ], [
            
            'slug' => 'unfall---x-unfallversicherung-mit-praemienrueckgewaehr',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Tontinengeschäfte'
        ], [
            
            'slug' => 'tontinengeschaefte',
            'description' => '',
            'weight' => 1,
            'is_active' => true,
            'average_rating_speed' => null,
            'average_rating_fairness' => null,
            'average_rating_service' => null,
            'order_column' => null
        ]);
        InsuranceSubtype::firstOrCreate([
            'name' => 'Zulassung als Sterbekasse'
        ], [
            
            'slug' => 'zulassung-als-sterbekasse',
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
