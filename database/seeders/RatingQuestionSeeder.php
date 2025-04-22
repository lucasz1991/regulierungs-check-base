<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RatingQuestion;

class RatingQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'title' => 'Regulierungsdauer',
                'question_text' => 'Wie viele Tage hat die Versicherung für die Regulierung gebraucht?',
                'type' => 'number',
                'is_required' => true,
                'meta' => ['unit' => 'Tage'],
                'help_text' => 'Zähle ab dem Erstkontakt bis zur Auszahlung.',
                'default_value' => null,
                'is_active' => true,
                'frontend_title' => 'Dauer der Regulierung',
                'frontend_description' => 'Wie lange hat es von Schadensmeldung bis zur Zahlung gedauert?',
                'weight' => 10,
                'input_constraints' => ['min' => 0, 'max' => 365],
                'read_only' => false,
                'tags' => ['dauer', 'regulierung'],
            ],
            [
                'title' => 'Fairness',
                'question_text' => 'Wie fair war die Entscheidung der Versicherung aus Ihrer Sicht?',
                'type' => 'rating',
                'is_required' => true,
                'meta' => ['scale' => 5],
                'help_text' => '1 = unfair, 5 = sehr fair',
                'default_value' => 3,
                'is_active' => true,
                'frontend_title' => 'Bewertung der Fairness',
                'frontend_description' => 'War die Entscheidung gerechtfertigt?',
                'weight' => 8,
                'input_constraints' => ['min' => 1, 'max' => 5],
                'read_only' => false,
                'tags' => ['fairness', 'entscheidung'],
            ],
            [
                'title' => 'Regulierungsbeginn',
                'question_text' => 'An welchem Datum hatten Sie das erste Gespräch mit der Versicherung?',
                'type' => 'date',
                'is_required' => false,
                'meta' => [],
                'help_text' => 'Bitte geben Sie das Startdatum der Kommunikation an.',
                'default_value' => null,
                'is_active' => true,
                'frontend_title' => 'Start der Regulierung',
                'frontend_description' => 'Wann ging es los?',
                'weight' => 5,
                'input_constraints' => [],
                'read_only' => false,
                'tags' => ['start', 'datum'],
            ],
            [
                'title' => 'Regulierungsende',
                'question_text' => 'Wann wurde die Regulierung abgeschlossen?',
                'type' => 'date',
                'is_required' => false,
                'meta' => [],
                'help_text' => 'Wann war alles abgeschlossen?',
                'default_value' => null,
                'is_active' => true,
                'frontend_title' => 'Ende der Regulierung',
                'frontend_description' => 'Abschlussdatum der Bearbeitung',
                'weight' => 5,
                'input_constraints' => [],
                'read_only' => false,
                'tags' => ['ende', 'datum'],
            ],
            [
                'title' => 'Service-Kommentar',
                'question_text' => 'Wie empfanden Sie den Kundenservice?',
                'type' => 'textarea',
                'is_required' => false,
                'meta' => [],
                'help_text' => 'Geben Sie Ihre Eindrücke zum Service wieder.',
                'default_value' => null,
                'is_active' => true,
                'frontend_title' => 'Kundenservice',
                'frontend_description' => 'Ihre Erfahrung mit der Kundenbetreuung',
                'weight' => 3,
                'input_constraints' => ['maxLength' => 500],
                'read_only' => false,
                'tags' => ['service', 'kommentar'],
            ],
        ];
        foreach ($questions as $question) {
            RatingQuestion::create($question);
        }
    }
}
