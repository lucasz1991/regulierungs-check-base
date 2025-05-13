<?php

namespace App\Http\Controllers\Customer\ClaimRating;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\Models\Setting;

class AIEvalController extends Controller
{

    public $apiUrl, $apiKey, $aiModel, $modelTitle, $refererUrl, $trainContent;

    public function mount()
    {
        $apiUrl = Setting::getValue('ai-scoring-settings', 'api_url');
        $apiKey = Setting::getValue('ai-scoring-settings', 'api_key');
        $aiModel = Setting::getValue('ai-scoring-settings', 'ai_model');
        $modelTitle = Setting::getValue('ai-scoring-settings', 'model_title');
        $refererUrl = Setting::getValue('ai-scoring-settings', 'referer_url');
        $trainContent = '';
        
    }

    static function getScoreForTextarea($question, $answer)
    {
        $query = [
            "question" => $question,
            "answer" => $answer,
        ]; 
        $trainContent = 'Du bist ein Assistent, der die Antwort eines Versicherungskunden analysiert. 
            Deine Aufgabe ist es, diese Antwort auf einer Skala von 0.01 (sehr negativ) bis 0.99 (sehr positiv) zu bewerten. 
            Beziehe dich dabei ausschließlich auf den Inhalt der Antwort, nicht auf andere Fragen.
            Berücksichtige:
            - Stimmung der Antwort (positiv, neutral, negativ)
            - Hinweise auf Probleme, Zufriedenheit oder Frust
            - Sprachliche Ausdrücke (z. B. Lob, Kritik, Frust, Dankbarkeit)
            Gib ausschließlich folgende JSON-Struktur zurück, ohne zusätzliche Erklärungen:
            {
            "question": "Service-Kommentar",
            "score": 0.63
            }';

        $isLoading = true;
        $maxRetries = 5;
        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
                try {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer '.$apiKey, 
                        'HTTP-Referer' => $refererUrl, 
                        'X-Title' => $modelTitle, 
                        'Content-Type'  => 'application/json',
                    ])->post($apiUrl, [
                        'model'    => $aiModel,
                        'messages' => array_merge([
                            [
                                'role'    => 'system',
                                'content' => trim(preg_replace('/\s+/', ' ', $trainContent))
                            ]
                        ], $query)
                    ]);
                    $botMessage = $response->json()['choices'][0]['message']['content'] ?? '';
                    Log::info($botMessage);
                    if (!empty($botMessage)) {
                        
                        $isLoading = false;
                        return $botMessage;
                    }
                } catch (\Exception $e) {
                }
        }
        return -1;
    }


    static function getScoreForRatingSpeed($qustion, $answer)
    {
        $this->trainContent = 'Du bist ein Assistent, der Bewertungen von Versicherungskunden analysiert. 
                Ziel ist es, auf Basis der Aussagen einen Score zwischen 0.01 (sehr schlecht) und 0.99 (sehr gut) zurückzugeben, der die Qualität der Schadenregulierung widerspiegelt.

                Bitte bewerte fair, nachvollziehbar und verständlich.

                Berücksichtige in deiner Analyse:
                - Wie schnell wurde der Schaden reguliert?
                - Wurde fair und transparent kommuniziert?
                - Gab es Rückfragen, Verzögerungen oder Ablehnungen?
                - Wie zufrieden ist der Kunde insgesamt?
                - Ist die Sprache positiv, neutral oder negativ?

                Gib ausschließlich folgende JSON-Struktur zurück:

                    [
                        "regulation_speed" => 0.32,
                        "customer_service" => 0.63,
                        "fairness" => 0.34,
                        "transparency" => 0.22,
                        "overall_satisfaction" => 0.20,
                    ]

                
                ';


        Log::info('Evaluating score for textarea', [
            'question' => $qustion,
            'answer' => $answer,
        ]);
        return 0.333;
    }
}
