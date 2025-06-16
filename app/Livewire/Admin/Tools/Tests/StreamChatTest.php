<?php

namespace App\Livewire\Admin\Tools\Tests;

use Livewire\Component;
use Livewire\Attributes\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class StreamChatTest extends Component
{
    #[Session] 
    public $chatHistory;
    #[Session] 
    public $lastResponse;
    
    public $message = '';
    public $isLoading = false;
    public $isFunctionCall = false;



    public $status, $assistantName, $apiUrl, $apiKey, $aiModel, $modelTitle, $refererUrl, $trainContent;

    protected $listeners = ['sendMessage' => 'sendMessage'];

    public function mount()
    {
        $this->status = Setting::getValue('ai_assistant', 'status');
        $this->assistantName = 'Denjo';
        $this->apiUrl = Setting::getValue('ai_assistant', 'api_url');
        $this->apiKey = Setting::getValue('ai_assistant', 'api_key');
        $this->aiModel = Setting::getValue('ai_assistant', 'ai_model');
        $this->modelTitle = Setting::getValue('ai_assistant', 'model_title');
        $this->refererUrl = Setting::getValue('ai_assistant', 'referer_url');
        $this->trainContent = <<<EOT
        Du bist der Regulierungs-Check Assistent mit dem Namen "Denjo" auf der Regulierungs-Check Website.
        Dein Name ist Denjo.

        -----


        1. Über Regulierungs-Check  
        1.1 Regulierungs-Check ist eine digitale Plattform zur Bewertung von Schadenregulierungen durch Versicherungen.  
        1.2 Kundinnen und Kunden können ihre Erfahrungen mit der Abwicklung eines Versicherungsfalls teilen und bewerten.  
        1.3 Die Plattform analysiert diese Bewertungen automatisch und erstellt objektive Qualitäts-Scores.  
        1.4 Ziel ist es, Transparenz in der Versicherungsbranche zu fördern und die besten Anbieter hervorzuheben.  
        1.5 Regulierungs-Check unterstützt Nutzer bei der Auswahl fairer Versicherungen und erkennt systematische Schwächen.

        2. Funktionen von Regulierungs-Check  
        2.1 Fragebogenbasierte Bewertung: Nutzer bewerten anhand von strukturierten Fragen ihre Schadenabwicklung.  
        2.2 Automatisierte Auswertung: Künstliche Intelligenz berechnet objektive Scores aus Freitext- und Skalenangaben.  
        2.3 Vergleich von Versicherungen: Versicherungsanbieter werden auf Basis echter Kundenerfahrungen gerankt.  
        2.4 Transparente Darstellung: Bewertungen sind öffentlich einsehbar und nach Typ, Anbieter oder Kategorie filterbar.  
        2.5 Qualitätssiegel & Score: Versicherungen mit besonders positiven Bewertungen erhalten ein Gütesiegel.  

        3. Vorteile für Nutzer  
        3.1 Orientierungshilfe: Nutzer sehen, wie fair und zuverlässig Versicherungen wirklich regulieren.  
        3.2 Einfluss auf Anbieter: Durch viele Bewertungen entsteht Druck auf Versicherungen, besser zu werden.  
        3.3 Transparenz schaffen: Die Plattform gibt Einblick in Prozesse, die sonst oft intransparent sind.  
        3.4 Zeitsparend & anonym: Die Bewertung dauert nur wenige Minuten und erfolgt ohne personenbezogene Angaben.

        4. Wie funktioniert Regulierungs-Check?  
        4.1 Nutzer wählen Versicherungstyp und geben an, wann und wie ein Schaden reguliert wurde.  
        4.2 Der strukturierte Fragebogen fragt nach Geschwindigkeit, Fairness, Kommunikation und Zufriedenheit.  
        4.3 Eine KI bewertet die Angaben objektiv und vergibt pro Kategorie einen Score zwischen 0.01 und 0.99.  
        4.4 Die Ergebnisse werden als Gesamtwert angezeigt und fließen in das Ranking des Versicherers ein.

        5. Wer kann Regulierungs-Check nutzen?  
        5.1 Privatpersonen, die eine Schadenregulierung erlebt haben.  
        5.2 Versicherte, die positive oder negative Erfahrungen teilen möchten.  
        5.3 Medien, Vergleichsportale und Verbraucherschützer auf der Suche nach realen Daten.  
        5.4 Versicherungen, die ihre Prozesse verbessern oder Feedback erhalten wollen.

        6. Technische Details  
        6.1 DSGVO-konform: Keine personenbezogenen Daten erforderlich.  
        6.2 JSON-Export: Bewertungen können für Analysen oder Schnittstellen exportiert werden.  
        6.3 API-Anbindung: Partner können Bewertungen direkt integrieren.  
        6.4 KI-gestützte Auswertung: Automatische Textanalyse für Freitext-Antworten.

        7. Warum jetzt mit Regulierungs-Check starten?  
        7.1 Verbraucher wollen faire Versicherungen – Regulierungs-Check hilft bei der Wahl.  
        7.2 Transparenz und Vergleichbarkeit werden zunehmend gefordert.  
        7.3 Versicherungen erhalten ein realistisches Feedback – anonym, ehrlich und strukturiert.  
        7.4 Die Plattform ist einfach zu bedienen und liefert sofort Ergebnisse.

        8. Dein nächster Schritt  
        📝 Bewertung abgeben: Teile deine Erfahrung und verbessere die Branche.  
        📊 Rankings ansehen: Finde heraus, welche Versicherungen wirklich fair regulieren.  
        📩 KI-Analyse testen: Lass deine Antwort automatisch bewerten und verstehe die Qualität deiner Regulierung.  
        🔍 Jetzt entdecken: Gib deiner Meinung eine Stimme und mach Schadenabwicklung vergleichbar!

        8.10 Antworten kurz und verständlich halten (maximal vier Sätze).  
        Bitte auf Deutsch antworten, es sei denn du wirst auf einer anderen Sprache etwas gefragt.  
        Dann sollst du auf Deutsch fragen, ob der Chat die Sprache ändern soll. Und ausschließlich deutsche Zeichen verwenden.

        Danke für deine Hilfe!
        EOT;
    }

    public function sendMessage()
    {
        if (trim($this->message) === '') {
            return;
        }

        // Benutzerfrage zur Historie hinzufügen
        //$this->chatHistory[] = ['role' => 'user', 'content' => $this->message];
        Log::info($this->message);

        $this->isLoading = true;
        $userMessage = $this->message;
        $this->message = '';

        // API-Call vorbereiten
        $maxRetries = 5;
        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$this->apiKey, 
                    'HTTP-Referer' => $this->refererUrl, 
                    'X-Title' => $this->modelTitle, 
                    'Content-Type'  => 'application/json',
                ])->post($this->apiUrl, [
                    'model'    => $this->aiModel,
                    'messages' => array_merge([
                        [
                            'role'    => 'system',
                            'content' => trim(preg_replace('/\s+/', ' ', $this->trainContent))
                        ]
                    ], $this->chatHistory),
                    'response_format' => [
                        'type' => 'json_schema',
                        'json_schema' => [
                            'name' => 'AnswerData',
                            'strict' => true,
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'answer' => [
                                        'type' => 'string',
                                        'description' => 'Die natürliche, vom Assistenten generierte Antwort in Klartext für den Nutzer.'
                                    ],
                                    'function_name' => [
                                        'type' => 'string',
                                        'enum' => ['none', 'navigate'],
                                        'description' => 'Name einer auszuführenden Funktion in der Benutzeroberfläche, z. B. "navigate" oder "none", falls keine Funktion vorgesehen ist.'
                                    ],
                                    'function_value' => [
                                        'type' => 'string',
                                        'enum' => ['', 'home', 'reviews', 'insurances', 'blog', 'aboutus', 'guidance', 'howto', 'contact', '#start-rating' ],
                                        'description' => 'Parameter zur Funktion, z. B. Zielroute oder ID. Muss leer sein, wenn function_name "none" ist.'
                                    ],
                                    'sentiment' => [
                                        'type' => 'string',
                                        'description' => 'Eingestufte Tonalität der Antwort: "neutral", "positiv", "negativ", um das Framing visuell zu unterstützen.'
                                    ],
                                    'call_to_action' => [
                                        'type' => 'string',
                                        'enum' => ['none', 'start-rating', 'show-insurances', 'show-reviews'],
                                        'description' => 'Optionaler Hinweis auf eine Handlungsempfehlung, z. B. "Bewertung starten", "Versicherungsanbieter anzeigen", "Bewertungen anzeigen"  oder none.'
                                    ],
                                    'tags' => [
                                        'type' => 'array',
                                        'items' => ['type' => 'string'],
                                        'description' => 'Stichwörter zur Kategorisierung des Themas (z. B. ["Verzögerung", "Auszahlung", "DKV"]).'
                                    ]
                                ],
                                'required' => ['answer', 'function_name', 'function_value', 'sentiment', 'call_to_action', 'tags'],
                                'additionalProperties' => false
                            ]
                        ]
                    ]
                    
                ]);
                $content = $response->json()['choices'][0]['message']['content'] ?? null;

                $decoded = json_decode($content, true); // true = as array

                $this->lastResponse = $decoded;

                $botMessage = $decoded['answer'] ?? '';

                if (!empty($botMessage)) {
                    // Bot message auf nicht deutsche zeichen filtern und entfernen 
                    $botMessage = preg_replace('/[\p{Han}\p{Hiragana}\p{Katakana}\p{Thai}]/u', '', $botMessage);
                    
                    $this->chatHistory[] = ['role' => 'assistant', 'content' => $botMessage];
                    
                    $this->isLoading = false;
                    if (!empty($decoded['function_name']) && $decoded['function_name'] === 'navigate') {
                        $target = $decoded['function_value'];
    
                        // Nur erlaubte Ziele verarbeiten
                        $allowedRoutes = ['home', 'reviews', 'insurances', 'blog', 'aboutus', 'guidance', 'howto', 'contact', '#start-rating'];
                        if (in_array($target, $allowedRoutes)) {
                            if ($target === 'home') {
                                return redirect()->to(url('/'));
                            }else{
                                return redirect()->to(url($target === '#' ? '/' : $target));
                            }
                        }
                    }
                    return;
                }

            } catch (\Exception $e) {
            }
        }

        // Falls nach 5 Versuchen keine Antwort kommt
        $this->chatHistory[] = ['role' => 'assistant', 'content' => "Ich habe dazu leider keine Antwort."];
        $this->isLoading = false;
    }

    public function clearChat()
    {
        $this->chatHistory = [];
    }

    public function render()
    {
        return view('livewire.admin.tools.tests.stream-chat-test')->layout('layouts.app');
    }
}
