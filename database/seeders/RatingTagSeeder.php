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
            ],
            [
                'tag' => 'schlechter Kontakt',
                'name' => 'Unzureichende Kommunikation',
                'description' => 'Mangelnde Information über den Stand der Schadensbearbeitung.',
            ],
            [
                'tag' => 'unklar',
                'name' => 'Unklare Versicherungsbedingungen',
                'description' => 'Schwierigkeit, die Versicherungsbedingungen zu verstehen.',
            ],
            [
                'tag' => 'Gutachterproblem',
                'name' => 'Probleme mit Gutachtern',
                'description' => 'Unzufriedenheit mit der Arbeit von Gutachtern.',
            ],
            [
                'tag' => 'zu wenig Leistung',
                'name' => 'Unzureichende Entschädigung',
                'description' => 'Entschädigungen, die nicht den tatsächlichen Schaden decken.',
            ],
            [
                'tag' => 'Nachweise schwierig',
                'name' => 'Schwierigkeiten bei der Beweisführung',
                'description' => 'Hohe Anforderungen an Nachweise oder Dokumente.',
            ],
            [
                'tag' => 'unfreundlich',
                'name' => 'Unfreundlicher Kundenservice',
                'description' => 'Negative Erfahrungen mit dem Kundenservice.',
            ],
            [
                'tag' => 'keine Antwort',
                'name' => 'Keine Rückmeldung',
                'description' => 'Ausbleiben von Antworten auf Anfragen oder Schadensmeldungen.',
            ],
            [
                'tag' => 'Zuständigkeit unklar',
                'name' => 'Unklare Zuständigkeiten',
                'description' => 'Unklarheit darüber, wer für welchen Teil des Prozesses verantwortlich ist.',
            ],
            [
                'tag' => 'Papierkrieg',
                'name' => 'Forderung unnötiger Dokumente',
                'description' => 'Anforderung von Dokumenten, die nicht relevant sind.',
            ],
            [
                'tag' => 'Technikfehler',
                'name' => 'Technische Probleme',
                'description' => 'Schwierigkeiten bei der Nutzung von Online-Diensten oder -Formularen.',
            ],
            [
                'tag' => 'widersprüchlich',
                'name' => 'Widersprüchliche Aussagen',
                'description' => 'Unterschiedliche Informationen von verschiedenen Ansprechpartnern.',
            ],
            [
                'tag' => 'Dokumente verloren',
                'name' => 'Verlust von Unterlagen',
                'description' => 'Eingereichte Dokumente gehen verloren oder werden nicht gefunden.',
            ],
            [
                'tag' => 'Lücke im Schutz',
                'name' => 'Lückenhafter Versicherungsschutz',
                'description' => 'Versicherungen decken nicht alle relevanten Risiken ab.',
            ],
            [
                'tag' => 'automatisch abgelehnt',
                'name' => 'Automatisierte Ablehnungen',
                'description' => 'Ablehnungen ohne individuelle Prüfung des Einzelfalls.',
            ],
            [
                'tag' => 'Zeitdruck',
                'name' => 'Zeitdruck durch Versicherung',
                'description' => 'Druck, schnell Entscheidungen zu treffen oder Dokumente einzureichen.',
            ],
            [
                'tag' => 'emotional belastend',
                'name' => 'Emotionale Belastung',
                'description' => 'Stress oder Frustration durch den gesamten Prozess.',
            ],
            [
                'tag' => 'Informationsmangel',
                'name' => 'Unzureichende Information',
                'description' => 'Mangelnde Aufklärung über den Stand des Verfahrens.',
            ],
            [
                'tag' => 'Ablehnung unklar',
                'name' => 'Unverständliche Ablehnungsgründe',
                'description' => 'Ablehnungen ohne nachvollziehbare Begründung.',
            ],
            [
                'tag' => 'Streit um Schadenshöhe',
                'name' => 'Probleme mit Schadenshöhe',
                'description' => 'Streitigkeiten über die Höhe des anerkannten Schadens.',
            ],
            [
                'tag' => 'Änderung intransparent',
                'name' => 'Fehlende Transparenz bei Vertragsänderungen',
                'description' => 'Änderungen werden nicht klar kommuniziert.',
            ],
            [
                'tag' => 'keine Hilfe',
                'name' => 'Unzureichende Unterstützung bei Schadensmeldung',
                'description' => 'Fehlende Hilfe bei der korrekten Meldung eines Schadens.',
            ],
            [
                'tag' => 'Zusatzleistung verzögert',
                'name' => 'Verzögerte Bearbeitung bei Zusatzleistungen',
                'description' => 'Verzögerungen bei der Bearbeitung von Zusatzleistungen oder -anträgen.',
            ],
            [
                'tag' => 'Unfallproblem',
                'name' => 'Unfallregulierung problematisch',
                'description' => 'Schwierigkeiten bei der Regulierung von Unfallschäden.',
            ],
            [
                'tag' => 'Fristen unklar',
                'name' => 'Unklare Fristen',
                'description' => 'Unklare oder nicht kommunizierte Fristen für Einreichungen.',
            ],
            [
                'tag' => 'Totalschaden unterbewertet',
                'name' => 'Totalschaden unzureichend entschädigt',
                'description' => 'Entschädigungen, die nicht den Wert des beschädigten Objekts decken.',
            ],
            [
                'tag' => 'Teilkasko problematisch',
                'name' => 'Probleme bei Teilkasko-Schäden',
                'description' => 'Streitigkeiten über die Höhe des anerkannten Schadens bei Teilkaskoversicherungen.',
            ],
            [
                'tag' => 'Mietwagen unklar',
                'name' => 'Mietwagenkosten unklar geregelt',
                'description' => 'Unklarheiten über die Übernahme von Mietwagenkosten nach einem Unfall.',
            ],
            [
                'tag' => 'Naturereignis problematisch',
                'name' => 'Naturkatastrophen nicht gut reguliert',
                'description' => 'Schwierigkeiten bei der Regulierung von Schäden durch Naturereignisse.',
            ],
            [
                'tag' => 'Regulierungshilfe fehlt',
                'name' => 'Fehlende Unterstützung bei Regulierung',
                'description' => 'Fehlende Unterstützung oder Beratung während des gesamten Prozesses.',
            ],
            [
                'tag' => 'Selbstbeteiligung unklar',
                'name' => 'Selbstbeteiligung unklar',
                'description' => 'Schwierigkeiten beim Verständnis oder der Anwendung von Selbstbeteiligungen.',
            ],
            [
                'tag' => 'Diebstahlproblem',
                'name' => 'Diebstahlschaden nicht korrekt reguliert',
                'description' => 'Schwierigkeiten bei der Regulierung von Diebstahlschäden.',
            ],
            [
                'tag' => 'Kommunikation schlecht',
                'name' => 'Schlechte Kommunikation bei Regulierung',
                'description' => 'Mangelnde Kommunikation über den Stand der Schadensbearbeitung.',
            ],
            [
                'tag' => 'Vandalismusproblem',
                'name' => 'Vandalismusschaden nicht gut reguliert',
                'description' => 'Schwierigkeiten bei der Regulierung von Vandalismusschäden.',
            ],
            [
                'tag' => 'Feuerproblem',
                'name' => 'Feuerschaden nicht gut reguliert',
                'description' => 'Fehlende Unterstützung bei der Regulierung von Feuerschäden.',
            ],
            [
                'tag' => 'Krankheit unklar',
                'name' => 'Krankheitsleistungen unklar',
                'description' => 'Schwierigkeiten beim Verständnis der Leistungen bei Krankheit.',
            ],
            [
                'tag' => 'Wasserschadenproblem',
                'name' => 'Wasserschaden nicht gut reguliert',
                'description' => 'Schwierigkeiten bei der Regulierung von Wasserschäden.',
            ],
            [
                'tag' => 'Vertragsänderung unklar',
                'name' => 'Vertragsänderung schlecht kommuniziert',
                'description' => 'Mangelnde Kommunikation über Änderungen der Versicherungsbedingungen.',
            ],
            [
                'tag' => 'Unfallleistung unklar',
                'name' => 'Unfallleistungen unklar',
                'description' => 'Schwierigkeiten beim Verständnis der Leistungen bei Unfällen.',
            ],
            [
                'tag' => 'schnell bearbeitet',
                'name' => 'Schnelle Bearbeitung',
                'description' => 'Der Schaden wurde zügig und ohne große Wartezeit bearbeitet.',
            ],
            [
                'tag' => 'gut erreichbar',
                'name' => 'Gute Erreichbarkeit',
                'description' => 'Die Versicherung war gut erreichbar und antwortete zeitnah.',
            ],
            [
                'tag' => 'klare Kommunikation',
                'name' => 'Klare Kommunikation',
                'description' => 'Der Kontakt war transparent und verständlich.',
            ],
            [
                'tag' => 'unkompliziert',
                'name' => 'Unkomplizierte Abwicklung',
                'description' => 'Die Abwicklung verlief reibungslos und ohne Hindernisse.',
            ],
            [
                'tag' => 'faire Entschädigung',
                'name' => 'Faire Entschädigung',
                'description' => 'Die ausgezahlte Summe war angemessen und nachvollziehbar.',
            ],
            [
                'tag' => 'kulant',
                'name' => 'Kulantes Verhalten',
                'description' => 'Die Versicherung zeigte sich entgegenkommend in der Regulierung.',
            ],
            [
                'tag' => 'freundlich',
                'name' => 'Guter Kundenservice',
                'description' => 'Die Ansprechpartner waren freundlich und hilfsbereit.',
            ],
            [
                'tag' => 'regelmäßige Rückmeldung',
                'name' => 'Verlässliche Rückmeldung',
                'description' => 'Die Versicherung meldete sich regelmäßig zum Stand der Dinge.',
            ],
            [
                'tag' => 'transparent',
                'name' => 'Transparente Bedingungen',
                'description' => 'Die Vertragsbedingungen waren verständlich und nachvollziehbar.',
            ],
            [
                'tag' => 'digital einfach',
                'name' => 'Digitale Abwicklung funktionierte gut',
                'description' => 'Onlineformulare und Uploads haben problemlos funktioniert.',
            ],
        ];
        foreach ($tags as $tagData) {
            $slug = Str::slug($tagData['tag']);
            RatingTag::updateOrCreate(
            ['tag' => $slug],
            [
                'name' => $tagData['name'],
                'description' => $tagData['description'] ?? null,
            ]
            );
        }
    }
}
