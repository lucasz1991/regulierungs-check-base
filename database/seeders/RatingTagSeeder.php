<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RatingTag;
use Illuminate\Support\Str;

class RatingTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'tag' => 'zu langsam',
                'name' => 'Lange Bearbeitungszeiten',
                'description' => 'Verzögerungen bei der Schadensbearbeitung.',
                'positivity' => 0.10,
            ],
            [
                'tag' => 'schlechter Kontakt',
                'name' => 'Unzureichende Kommunikation',
                'description' => 'Mangelnde Information über den Stand der Schadensbearbeitung.',
                'positivity' => 0.10,
            ],
            [
                'tag' => 'unklar',
                'name' => 'Unklare Versicherungsbedingungen',
                'description' => 'Schwierigkeit, die Versicherungsbedingungen zu verstehen.',
                'positivity' => 0.15,
            ],
            [
                'tag' => 'Gutachterproblem',
                'name' => 'Probleme mit Gutachtern',
                'description' => 'Unzufriedenheit mit der Arbeit von Gutachtern.',
                'positivity' => 0.10,
            ],
            [
                'tag' => 'zu wenig Leistung',
                'name' => 'Unzureichende Entschädigung',
                'description' => 'Entschädigungen, die nicht den tatsächlichen Schaden decken.',
                'positivity' => 0.08,
            ],
            [
                'tag' => 'Nachweise schwierig',
                'name' => 'Schwierigkeiten bei der Beweisführung',
                'description' => 'Hohe Anforderungen an Nachweise oder Dokumente.',
                'positivity' => 0.12,
            ],
            [
                'tag' => 'unfreundlich',
                'name' => 'Unfreundlicher Kundenservice',
                'description' => 'Negative Erfahrungen mit dem Kundenservice.',
                'positivity' => 0.05,
            ],
            [
                'tag' => 'keine Antwort',
                'name' => 'Keine Rückmeldung',
                'description' => 'Ausbleiben von Antworten auf Anfragen oder Schadensmeldungen.',
                'positivity' => 0.06,
            ],
            [
                'tag' => 'Zuständigkeit unklar',
                'name' => 'Unklare Zuständigkeiten',
                'description' => 'Unklarheit darüber, wer für welchen Teil des Prozesses verantwortlich ist.',
                'positivity' => 0.12,
            ],
            [
                'tag' => 'Papierkrieg',
                'name' => 'Forderung unnötiger Dokumente',
                'description' => 'Anforderung von Dokumenten, die nicht relevant sind.',
                'positivity' => 0.09,
            ],
            [
                'tag' => 'Technikfehler',
                'name' => 'Technische Probleme',
                'description' => 'Schwierigkeiten bei der Nutzung von Online-Diensten oder -Formularen.',
                'positivity' => 0.13,
            ],
            [
                'tag' => 'widersprüchlich',
                'name' => 'Widersprüchliche Aussagen',
                'description' => 'Unterschiedliche Informationen von verschiedenen Ansprechpartnern.',
                'positivity' => 0.10,
            ],
            [
                'tag' => 'Dokumente verloren',
                'name' => 'Verlust von Unterlagen',
                'description' => 'Eingereichte Dokumente gehen verloren oder werden nicht gefunden.',
                'positivity' => 0.07,
            ],
            [
                'tag' => 'Lücke im Schutz',
                'name' => 'Lückenhafter Versicherungsschutz',
                'description' => 'Versicherungen decken nicht alle relevanten Risiken ab.',
                'positivity' => 0.11,
            ],
            [
                'tag' => 'automatisch abgelehnt',
                'name' => 'Automatisierte Ablehnungen',
                'description' => 'Ablehnungen ohne individuelle Prüfung des Einzelfalls.',
                'positivity' => 0.08,
            ],
            [
                'tag' => 'Zeitdruck',
                'name' => 'Zeitdruck durch Versicherung',
                'description' => 'Druck, schnell Entscheidungen zu treffen oder Dokumente einzureichen.',
                'positivity' => 0.13,
            ],
            [
                'tag' => 'emotional belastend',
                'name' => 'Emotionale Belastung',
                'description' => 'Stress oder Frustration durch den gesamten Prozess.',
                'positivity' => 0.07,
            ],
            [
                'tag' => 'Informationsmangel',
                'name' => 'Unzureichende Information',
                'description' => 'Mangelnde Aufklärung über den Stand des Verfahrens.',
                'positivity' => 0.10,
            ],
            [
                'tag' => 'Ablehnung unklar',
                'name' => 'Unverständliche Ablehnungsgründe',
                'description' => 'Ablehnungen ohne nachvollziehbare Begründung.',
                'positivity' => 0.09,
            ],
            [
                'tag' => 'Streit um Schadenshöhe',
                'name' => 'Probleme mit Schadenshöhe',
                'description' => 'Streitigkeiten über die Höhe des anerkannten Schadens.',
                'positivity' => 0.12,
            ],
            [
                'tag' => 'Änderung intransparent',
                'name' => 'Fehlende Transparenz bei Vertragsänderungen',
                'description' => 'Änderungen werden nicht klar kommuniziert.',
                'positivity' => 0.13,
            ],
            [
                'tag' => 'keine Hilfe',
                'name' => 'Unzureichende Unterstützung bei Schadensmeldung',
                'description' => 'Fehlende Hilfe bei der korrekten Meldung eines Schadens.',
                'positivity' => 0.09,
            ],
            [
                'tag' => 'Zusatzleistung verzögert',
                'name' => 'Verzögerte Bearbeitung bei Zusatzleistungen',
                'description' => 'Verzögerungen bei der Bearbeitung von Zusatzleistungen oder -anträgen.',
                'positivity' => 0.14,
            ],
            [
                'tag' => 'Unfallproblem',
                'name' => 'Unfallregulierung problematisch',
                'description' => 'Schwierigkeiten bei der Regulierung von Unfallschäden.',
                'positivity' => 0.13,
            ],
            [
                'tag' => 'Fristen unklar',
                'name' => 'Unklare Fristen',
                'description' => 'Unklare oder nicht kommunizierte Fristen für Einreichungen.',
                'positivity' => 0.12,
            ],
            [
                'tag' => 'Totalschaden unterbewertet',
                'name' => 'Totalschaden unzureichend entschädigt',
                'description' => 'Entschädigungen, die nicht den Wert des beschädigten Objekts decken.',
                'positivity' => 0.10,
            ],
            [
                'tag' => 'Teilkasko problematisch',
                'name' => 'Probleme bei Teilkasko-Schäden',
                'description' => 'Streitigkeiten über die Höhe des anerkannten Schadens bei Teilkaskoversicherungen.',
                'positivity' => 0.13,
            ],
            [
                'tag' => 'Mietwagen unklar',
                'name' => 'Mietwagenkosten unklar geregelt',
                'description' => 'Unklarheiten über die Übernahme von Mietwagenkosten nach einem Unfall.',
                'positivity' => 0.14,
            ],
            [
                'tag' => 'Naturereignis problematisch',
                'name' => 'Naturkatastrophen nicht gut reguliert',
                'description' => 'Schwierigkeiten bei der Regulierung von Schäden durch Naturereignisse.',
                'positivity' => 0.13,
            ],
            [
                'tag' => 'Regulierungshilfe fehlt',
                'name' => 'Fehlende Unterstützung bei Regulierung',
                'description' => 'Fehlende Unterstützung oder Beratung während des gesamten Prozesses.',
                'positivity' => 0.09,
            ],
            [
                'tag' => 'Selbstbeteiligung unklar',
                'name' => 'Selbstbeteiligung unklar',
                'description' => 'Schwierigkeiten beim Verständnis oder der Anwendung von Selbstbeteiligungen.',
                'positivity' => 0.15,
            ],
            [
                'tag' => 'Diebstahlproblem',
                'name' => 'Diebstahlschaden nicht korrekt reguliert',
                'description' => 'Schwierigkeiten bei der Regulierung von Diebstahlschäden.',
                'positivity' => 0.12,
            ],
            [
                'tag' => 'Kommunikation schlecht',
                'name' => 'Schlechte Kommunikation bei Regulierung',
                'description' => 'Mangelnde Kommunikation über den Stand der Schadensbearbeitung.',
                'positivity' => 0.10,
            ],
            [
                'tag' => 'Vandalismusproblem',
                'name' => 'Vandalismusschaden nicht gut reguliert',
                'description' => 'Schwierigkeiten bei der Regulierung von Vandalismusschäden.',
                'positivity' => 0.13,
            ],
            [
                'tag' => 'Feuerproblem',
                'name' => 'Feuerschaden nicht gut reguliert',
                'description' => 'Fehlende Unterstützung bei der Regulierung von Feuerschäden.',
                'positivity' => 0.13,
            ],
            [
                'tag' => 'Krankheit unklar',
                'name' => 'Krankheitsleistungen unklar',
                'description' => 'Schwierigkeiten beim Verständnis der Leistungen bei Krankheit.',
                'positivity' => 0.15,
            ],
            [
                'tag' => 'Wasserschadenproblem',
                'name' => 'Wasserschaden nicht gut reguliert',
                'description' => 'Schwierigkeiten bei der Regulierung von Wasserschäden.',
                'positivity' => 0.13,
            ],
            [
                'tag' => 'Vertragsänderung unklar',
                'name' => 'Vertragsänderung schlecht kommuniziert',
                'description' => 'Mangelnde Kommunikation über Änderungen der Versicherungsbedingungen.',
                'positivity' => 0.14,
            ],
            [
                'tag' => 'Unfallleistung unklar',
                'name' => 'Unfallleistungen unklar',
                'description' => 'Schwierigkeiten beim Verständnis der Leistungen bei Unfällen.',
                'positivity' => 0.15,
            ],
            [
                'tag' => 'schnell bearbeitet',
                'name' => 'Schnelle Bearbeitung',
                'description' => 'Der Schaden wurde zügig und ohne große Wartezeit bearbeitet.',
                'positivity' => 0.95,
            ],
            [
                'tag' => 'gut erreichbar',
                'name' => 'Gute Erreichbarkeit',
                'description' => 'Die Versicherung war gut erreichbar und antwortete zeitnah.',
                'positivity' => 0.90,
            ],
            [
                'tag' => 'klare Kommunikation',
                'name' => 'Klare Kommunikation',
                'description' => 'Der Kontakt war transparent und verständlich.',
                'positivity' => 0.92,
            ],
            [
                'tag' => 'unkompliziert',
                'name' => 'Unkomplizierte Abwicklung',
                'description' => 'Die Abwicklung verlief reibungslos und ohne Hindernisse.',
                'positivity' => 0.97,
            ],
            [
                'tag' => 'faire Entschädigung',
                'name' => 'Faire Entschädigung',
                'description' => 'Die ausgezahlte Summe war angemessen und nachvollziehbar.',
                'positivity' => 0.93,
            ],
            [
                'tag' => 'kulant',
                'name' => 'Kulantes Verhalten',
                'description' => 'Die Versicherung zeigte sich entgegenkommend in der Regulierung.',
                'positivity' => 0.96,
            ],
            [
                'tag' => 'freundlich',
                'name' => 'Guter Kundenservice',
                'description' => 'Die Ansprechpartner waren freundlich und hilfsbereit.',
                'positivity' => 0.99,
            ],
            [
                'tag' => 'regelmäßige Rückmeldung',
                'name' => 'Verlässliche Rückmeldung',
                'description' => 'Die Versicherung meldete sich regelmäßig zum Stand der Dinge.',
                'positivity' => 0.94,
            ],
            [
                'tag' => 'transparent',
                'name' => 'Transparente Bedingungen',
                'description' => 'Die Vertragsbedingungen waren verständlich und nachvollziehbar.',
                'positivity' => 0.91,
            ],
            [
                'tag' => 'digital einfach',
                'name' => 'Digitale Abwicklung funktionierte gut',
                'description' => 'Onlineformulare und Uploads haben problemlos funktioniert.',
                'positivity' => 0.98,
            ],
        ];
        foreach ($tags as $tagData) {
            $slug = Str::slug($tagData['tag']);
            RatingTag::updateOrCreate(
                ['tag' => $slug],
                [
                    'name' => $tagData['name'],
                    'description' => $tagData['description'] ?? null,
                    'positivity' => $tagData['positivity'] ?? null,
                ]
            );
        }
    }
}
