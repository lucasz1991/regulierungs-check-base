<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RatingTag;

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
                'tag' => 'Ausland schwierig',
                'name' => 'Schadensregulierung im Ausland',
                'description' => 'Schwierigkeiten bei der Regulierung von Schäden, die im Ausland entstanden sind.',
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
                'tag' => 'Auslandsleistung unklar',
                'name' => 'Leistung im Ausland unklar',
                'description' => 'Unklarheiten über die Abdeckung von Leistungen im Ausland.',
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
                'tag' => 'zu langsam_var',
                'name' => 'Lange Bearbeitungszeiten (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Lange Bearbeitungszeiten', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'schlechter Kontakt_var',
                'name' => 'Unzureichende Kommunikation (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unzureichende Kommunikation', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'unklar_var',
                'name' => 'Unklare Versicherungsbedingungen (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unklare Versicherungsbedingungen', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Gutachterproblem_var',
                'name' => 'Probleme mit Gutachtern (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Probleme mit Gutachtern', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'zu wenig Leistung_var',
                'name' => 'Unzureichende Entschädigung (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unzureichende Entschädigung', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Nachweise schwierig_var',
                'name' => 'Schwierigkeiten bei der Beweisführung (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Schwierigkeiten bei der Beweisführung', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'unfreundlich_var',
                'name' => 'Unfreundlicher Kundenservice (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unfreundlicher Kundenservice', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'keine Antwort_var',
                'name' => 'Keine Rückmeldung (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Keine Rückmeldung', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Zuständigkeit unklar_var',
                'name' => 'Unklare Zuständigkeiten (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unklare Zuständigkeiten', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Papierkrieg_var',
                'name' => 'Forderung unnötiger Dokumente (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Forderung unnötiger Dokumente', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Technikfehler_var',
                'name' => 'Technische Probleme (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Technische Probleme', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'widersprüchlich_var',
                'name' => 'Widersprüchliche Aussagen (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Widersprüchliche Aussagen', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Dokumente verloren_var',
                'name' => 'Verlust von Unterlagen (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Verlust von Unterlagen', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Lücke im Schutz_var',
                'name' => 'Lückenhafter Versicherungsschutz (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Lückenhafter Versicherungsschutz', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'automatisch abgelehnt_var',
                'name' => 'Automatisierte Ablehnungen (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Automatisierte Ablehnungen', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Zeitdruck_var',
                'name' => 'Zeitdruck durch Versicherung (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Zeitdruck durch Versicherung', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'emotional belastend_var',
                'name' => 'Emotionale Belastung (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Emotionale Belastung', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Informationsmangel_var',
                'name' => 'Unzureichende Information (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unzureichende Information', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Ablehnung unklar_var',
                'name' => 'Unverständliche Ablehnungsgründe (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unverständliche Ablehnungsgründe', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Streit um Schadenshöhe_var',
                'name' => 'Probleme mit Schadenshöhe (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Probleme mit Schadenshöhe', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Änderung intransparent_var',
                'name' => 'Fehlende Transparenz bei Vertragsänderungen (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Fehlende Transparenz bei Vertragsänderungen', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Ausland schwierig_var',
                'name' => 'Schadensregulierung im Ausland (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Schadensregulierung im Ausland', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'keine Hilfe_var',
                'name' => 'Unzureichende Unterstützung bei Schadensmeldung (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unzureichende Unterstützung bei Schadensmeldung', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Zusatzleistung verzögert_var',
                'name' => 'Verzögerte Bearbeitung bei Zusatzleistungen (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Verzögerte Bearbeitung bei Zusatzleistungen', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Unfallproblem_var',
                'name' => 'Unfallregulierung problematisch (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unfallregulierung problematisch', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Fristen unklar_var',
                'name' => 'Unklare Fristen (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unklare Fristen', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Totalschaden unterbewertet_var',
                'name' => 'Totalschaden unzureichend entschädigt (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Totalschaden unzureichend entschädigt', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Teilkasko problematisch_var',
                'name' => 'Probleme bei Teilkasko-Schäden (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Probleme bei Teilkasko-Schäden', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Mietwagen unklar_var',
                'name' => 'Mietwagenkosten unklar geregelt (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Mietwagenkosten unklar geregelt', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Naturereignis problematisch_var',
                'name' => 'Naturkatastrophen nicht gut reguliert (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Naturkatastrophen nicht gut reguliert', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Regulierungshilfe fehlt_var',
                'name' => 'Fehlende Unterstützung bei Regulierung (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Fehlende Unterstützung bei Regulierung', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Selbstbeteiligung unklar_var',
                'name' => 'Selbstbeteiligung unklar (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Selbstbeteiligung unklar', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Diebstahlproblem_var',
                'name' => 'Diebstahlschaden nicht korrekt reguliert (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Diebstahlschaden nicht korrekt reguliert', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Kommunikation schlecht_var',
                'name' => 'Schlechte Kommunikation bei Regulierung (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Schlechte Kommunikation bei Regulierung', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Auslandsleistung unklar_var',
                'name' => 'Leistung im Ausland unklar (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Leistung im Ausland unklar', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Vandalismusproblem_var',
                'name' => 'Vandalismusschaden nicht gut reguliert (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Vandalismusschaden nicht gut reguliert', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Feuerproblem_var',
                'name' => 'Feuerschaden nicht gut reguliert (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Feuerschaden nicht gut reguliert', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Krankheit unklar_var',
                'name' => 'Krankheitsleistungen unklar (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Krankheitsleistungen unklar', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Wasserschadenproblem_var',
                'name' => 'Wasserschaden nicht gut reguliert (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Wasserschaden nicht gut reguliert', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Vertragsänderung unklar_var',
                'name' => 'Vertragsänderung schlecht kommuniziert (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Vertragsänderung schlecht kommuniziert', aber in anderem Kontext aufgetreten.",
            ],
            [
                'tag' => 'Unfallleistung unklar_var',
                'name' => 'Unfallleistungen unklar (ähnlicher Fall)',
                'description' => "Ein vergleichbares Problem wie 'Unfallleistungen unklar', aber in anderem Kontext aufgetreten.",
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
            RatingTag::updateOrCreate(
                ['tag' => $tagData['tag']],
                [
                    'name' => $tagData['name'],
                    'description' => $tagData['description'] ?? null,
                ]
            );
        }
    }
}
