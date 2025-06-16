<?php

namespace App\Livewire\Tools;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Session;
use App\Models\Setting;


class Chatbot extends Component
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
        $this->assistantName = Setting::getValue('ai_assistant', 'assistant_name');
        $this->apiUrl = Setting::getValue('ai_assistant', 'api_url');
        $this->apiKey = Setting::getValue('ai_assistant', 'api_key');
        $this->aiModel = Setting::getValue('ai_assistant', 'ai_model');
        $this->modelTitle = Setting::getValue('ai_assistant', 'model_title');
        $this->refererUrl = Setting::getValue('ai_assistant', 'referer_url');
        $this->trainContent = Setting::getValue('ai_assistant', 'train_content');
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
        return view('livewire.tools.chatbot');
    }
}
