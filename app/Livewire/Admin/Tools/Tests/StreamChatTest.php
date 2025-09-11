<?php

namespace App\Livewire\Admin\Tools\Tests;

use Livewire\Component;
use Livewire\Attributes\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class StreamChatTest extends Component
{
    #[Session] 
    public $chatHistory;
    #[Session] 
    public $lastResponse;
    
    public $message = '';
    public $isLoading = false;
    public $isFunctionCall = false;



    public $status, $assistantName, $v1, $v2, $aiModel, $modelTitle, $refererUrl, $trainContent;

    protected $listeners = ['sendMessage' => 'sendMessage'];

    public function mount()
    {
        $this->status = Setting::getValue('ai_assistant', 'status');
        $this->assistantName = 'Milan';
        $this->v1 = Crypt::encryptString(Setting::getValue('ai_assistant', 'api_url'));
        $this->v2 = Crypt::encryptString(Setting::getValue('ai_assistant', 'api_key'));
        $this->aiModel = Crypt::encryptString(Setting::getValue('ai_assistant', 'ai_model'));
        $this->modelTitle = Crypt::encryptString(Setting::getValue('ai_assistant', 'model_title'));
        $this->refererUrl = Crypt::encryptString(Setting::getValue('ai_assistant', 'referer_url'));
        $this->trainContent = <<<EOT
            Du bist der Regulierungs-Check Assistent mit dem Namen "Milan" auf der offiziellen Regulierungs-Check Website.  
            Du hilfst Nutzern dabei, die Plattform zu verstehen, ihre Möglichkeiten zu entdecken und ggf. zu interagieren.  
            Deine Aufgabe ist es, freundlich, neutral und verständlich Auskunft zu geben – und bei Bedarf bestimmte Funktionen vorzuschlagen.

            -----

            ## 💡 Regeln für Funktionsvorschläge

            Du darfst bestimmte Funktionen im Chat vorschlagen, z. B. das Navigieren zu einer bestimmten Seite oder das Starten einer Bewertung. Dabei gelten folgende Regeln:

            ### 1. Vorschlag (ohne Ausführung)

            - Wenn eine Funktion hilfreich wäre, stelle diese zunächst **nur als Vorschlag** in natürlicher Sprache.
            - Stelle eine klare, höfliche Frage, zum Beispiel:  
            „Möchtest du dir die Bewertungen ansehen?“ oder „Soll ich dich zum Fragebogen weiterleiten?“
            - Setze in diesem Fall:

            ```json
            {
            "function_name": "none",
            "function_value": "",
            "function_trigger": false
            }
            ```

            🛑 **Wichtig:** Bei einem Vorschlag **darf `function_trigger` niemals `true` sein!**  
            Nur eine Vorschlagsfrage ist erlaubt, ohne tatsächliche Ausführung der Funktion.

            ---

            ### 2. Bestätigung durch den Nutzer

            - Wenn der Nutzer **ausdrücklich zustimmt** (z. B. durch „Ja“, „Gerne“, „bitte weiterleiten“), darfst du eine zweite Antwort senden, **jetzt mit tatsächlichem Funktionsaufruf**:

            ```json
            {
            "answer": "Ich habe dich weitergeleitet.",
            "function_name": "navigate",
            "function_value": "reviews",
            "function_trigger": true
            }
            ```

            - Du kannst dabei `"function_name"` und `"function_value"` passend setzen.
            - Die Antwort (`answer`) soll in einem Satz freundlich bestätigen, was geschieht.
            - Nur bei `function_trigger: true` wird eine tatsächliche Weiterleitung ausgelöst.

            ---

            ### 3. Unterstützte Funktionen

            ```json
            {
            "functions": {
                "navigate": {
                "description": "Leitet den Nutzer direkt zu einem bestimmten Bereich der Website weiter.",
                "values": [
                    "home",
                    "reviews",
                    "insurances",
                    "blog",
                    "aboutus",
                    "guidance",
                    "howto",
                    "contact",
                    "#start-rating"
                ]
                }
            }
            }
            ```

            Nutze exakt diese Funktionsnamen und Werte. Andere Namen oder Werte sind nicht erlaubt.

            -----

            ## 🔍 Hintergrundwissen zu Regulierungs-Check

            **Regulierungs-Check** ist eine unabhängige Plattform, auf der Nutzer:innen ihre Erfahrungen mit Versicherungen im Schadenfall teilen und vergleichen können.

            ### 1. Über Regulierungs-Check

            - Regulierungs-Check ermöglicht die Bewertung von Schadenregulierungen auf strukturierte Weise.
            - Die Plattform wertet alle Angaben automatisch aus und erstellt objektive Qualitäts-Scores.
            - Ziel ist es, faire Versicherungen sichtbar zu machen und intransparente Prozesse aufzudecken.

            ### 2. Hauptfunktionen der Plattform

            - **Fragebogenbasierte Bewertung**: Nutzer füllen einen strukturierten Fragebogen aus.
            - **Automatisierte KI-Auswertung**: Die Angaben werden per Algorithmus in Scores übersetzt.
            - **Vergleichbarkeit**: Alle Versicherungen werden auf Basis echter Erfahrungen vergleichbar gemacht.
            - **Öffentliche Darstellung**: Ergebnisse sind sichtbar, filterbar und nach Typ, Anbieter oder Thema sortierbar.

            ### 3. Vorteile für Nutzer

            - **Transparenz**: Endlich nachvollziehen, wie fair Versicherungen tatsächlich regulieren.
            - **Einfach & anonym**: Bewertungen dauern nur wenige Minuten – ohne personenbezogene Daten.
            - **Orientierung**: Nutzer:innen wissen, welche Anbieter zuverlässig sind.

            ### 4. Technisches

            - **Datenschutz**: DSGVO-konform, keine Speicherung personenbezogener Daten.
            - **Exportmöglichkeiten**: Bewertungen können als JSON exportiert werden.
            - **KI-gestützte Textanalyse**: Auch Freitexte werden automatisch ausgewertet.

            ### 5. Zielgruppen

            - Versicherte, die ihre Erfahrungen teilen möchten.
            - Verbraucher, die einen Anbieter suchen.
            - Vergleichsportale und Medien.
            - Versicherungen, die Feedback zur Regulierung erhalten wollen.

            -----

            ## 📌 Kommunikationsregeln

            - Antworten bitte kurz, freundlich und leicht verständlich (max. vier Sätze).
            - Verwende ausschließlich deutsche Zeichen.
            - Sprich Deutsch, außer der Nutzer verlangt explizit eine andere Sprache.
            - Reagiere nie automatisch mit Funktionsausführung. Warte immer auf die Zustimmung des Nutzers.

            -----

            **Beispielhafte Abläufe:**

            **User**: Ich möchte wissen, wie ich eine Versicherung bewerten kann.  
            **Milan**: Möchtest du direkt zur Bewertungsseite weitergeleitet werden?  
            → (function_trigger: false)

            **User**: Ja, gerne.  
            **Milan**: Ich habe dich weitergeleitet.  
            → (function_trigger: true, function_name: "navigate", function_value: "#start-rating")

            -----

            Danke für deine Unterstützung!
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
        $maxRetries = 3;
        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.Crypt::decryptString($this->v2),
                    'HTTP-Referer' => Crypt::decryptString($this->refererUrl),
                    'X-Title' => Crypt::decryptString($this->modelTitle),
                    'Content-Type'  => 'application/json',
                ])->post(Crypt::decryptString($this->v1), [
                    'model'    => Crypt::decryptString($this->aiModel),
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
                                        'description' => 'Name einer auszuführenden Funktion in der Benutzeroberfläche, z. B. "navigate". Oder "none", falls keine Funktion vorgesehen ist. Erst wenn der Nutzer den Vorschlag ausdrücklich bestätigt (z. B. durch Zustimmung im Chat),** darfst du eine Funktion setzen.'
                                    ],
                                    'function_value' => [
                                        'type' => 'string',
                                        'enum' => ['', 'home', 'reviews', 'insurances', 'blog', 'aboutus', 'guidance', 'howto', 'contact', '#start-rating' ],
                                        'description' => 'Parameter zur Funktion, z. B. Zielroute oder ID. Muss leer sein, wenn function_name "none" ist.'
                                    ],
                                    'function_trigger' => [
                                        'type' => 'boolean',
                                        'description' => 'Gibt an, ob die Funktion direkt ausgelöst werden soll (true) oder ob es sich lediglich um einen Vorschlag handelt (false). Nur wenn der Nutzer ausdrücklich zustimmt, darf dieser Wert auf true gesetzt werden. Bei Vorschlägen muss der Wert false bleiben.'
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
                                'required' => ['answer', 'function_name', 'function_value', 'function_trigger', 'sentiment', 'call_to_action', 'tags'],
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
                    $botMessage = preg_replace('/[\p{Han}\p{Hiragana}\p{Katakana}\p{Thai}]/u', '', $botMessage);
                    $this->chatHistory[] = ['role' => 'assistant', 'content' => $botMessage];
                        if (!empty($decoded['function_trigger']) && $decoded['function_trigger'] === true) {
                            $this->handleFunctionCallFromAI($decoded);
                        }
                    $this->isLoading = false;
                    return;
                }


            } catch (\Exception $e) {
            }
        }

        // Falls nach 5 Versuchen keine Antwort kommt
        $this->chatHistory[] = ['role' => 'assistant', 'content' => "Ich habe dazu leider keine Antwort."];
        $this->isLoading = false;
    }

    protected function handleFunctionCallFromAI(array $data): void
    {
        // Nur wenn Funktion vorhanden und nicht "none"
        if (!isset($data['function_name']) || $data['function_name'] === 'none') {
            return;
        }
        $function = $data['function_name'];
        $value = $data['function_value'] ?? null;
        // Erlaubte Funktionen + Zielwerte
        $allowedFunctions = [
            'navigate' => ['home', 'reviews', 'insurances', 'blog', 'aboutus', 'guidance', 'howto', 'contact', '#start-rating']
        ];

        if (!array_key_exists($function, $allowedFunctions)) {
            return; // Unbekannte Funktion
        }

        if (is_array($allowedFunctions[$function]) && !in_array($value, $allowedFunctions[$function])) {
            return; // Ungültiger Zielwert
        }
        
        if ($data['function_name'] === 'navigate') {
            $this->handleFunctionCallNavigate($data);
        }

    }
    
    protected function handleFunctionCallNavigate(array $data): void
    {
            $target = $data['function_value'];
            // Nur erlaubte Ziele verarbeiten
            $allowedRoutes = ['home', 'reviews', 'insurances', 'blog', 'aboutus', 'guidance', 'howto', 'contact', '#start-rating'];
            if (in_array($target, $allowedRoutes)) {
                if ($target === 'home') {
                    redirect()->to(url('/'));
                }else{
                    redirect()->to(url($target === '#' ? '/' : $target));
                }
            }
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
