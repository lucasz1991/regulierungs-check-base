<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Insurance;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class AnalyzeInsuranceOnlineViaGpt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Insurance $insurance;

    public function __construct(Insurance $insurance)
    {
        $this->insurance = $insurance;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $apiUrl = Setting::getValue('ai-scoring-settings', 'api_url');
        $apiKey = Setting::getValue('ai-scoring-settings', 'api_key');
        $aiModel = Setting::getValue('ai-scoring-settings', 'ai_model');
        $modelTitle = Setting::getValue('ai-scoring-settings', 'model_title');
        $refererUrl = Setting::getValue('ai-scoring-settings', 'referer_url');

        $trainContent = <<<EOT
        Du bist ein Assistent für Webanalyse, spezialisiert auf Versicherungsunternehmen in Deutschland.
        
        Deine Aufgabe ist es, anhand des angegebenen Versicherungsnamens folgende Informationen in strukturierter Form bereitzustellen:
        - Eine kurze, neutrale Beschreibung der Versicherung.
        - Eine passende Hintergrundfarbe für das Logo (als HEX-Farbcode), die zur visuellen Identität der Marke passt.
        - Eine passende Schriftfarbe für das Logo (ebenfalls HEX).
        - Eine kurze Abkürzung (max. 8 Zeichen), die sich gut für interne Systemdarstellungen eignet.
        
        Wichtige Hinweise:
        - Verwende öffentlich verfügbare Informationen.
        - Wenn du die Farben nicht genau kennst, schätze sie basierend auf dem typischen Markenauftritt.
        - Verwende keine zusätzlichen Kommentare, Erklärungen oder Anmerkungen.
        - Gib ausschließlich ein JSON-Objekt zurück.
        
        Beispielhafte Struktur der Antwort:
        {
          "description": "Kurzbeschreibung der Versicherung",
          "logo_bg_color": "#000000",
          "logo_font_color": "#FFFFFF",
          "logo_border_color": "#FFFFFF",
          "abbreviation": "XYZfds" // Maximal 8 Zeichen , z.B. "VersA", "Allianz", "HUK24"
        }
        EOT;
        $attachments = $this->insurance->name;
        $isLoading = true;
        $maxRetries = 3;
        
        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            
            try {
                $response = Http::timeout(120)->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'HTTP-Referer' => $refererUrl,
                    'X-Title' => $modelTitle,
                    'Content-Type' => 'application/json',
                ])->post($apiUrl, [
                    'model' => $aiModel,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => trim(preg_replace('/\s+/', ' ', $trainContent)),
                        ],
                        [
                            'role' => 'user',
                            'content' => <<<TEXT
                                attachments: {$attachments}
                                TEXT
                        ]
                    ]
                ]);
                Log::info($response->json());
                if ($response->failed()) {
                    $statusCode = $response->status();
                    if ($statusCode >= 400 && $statusCode < 500) {
                        throw new \Exception("Client error occurred: $statusCode");
                    } elseif ($statusCode >= 500) {
                        throw new \Exception("Server error occurred: $statusCode");
                    }
                }
                $botMessage = $response->json()['choices'][0]['message']['content'] ?? '';
                
                if (!empty($botMessage)) {
                    Log::info("Raw Message: $botMessage");
                    $cleanedbotMessage = preg_replace('/^```(json)?|```$/m', '', trim($botMessage));

                    $decodedMessage = json_decode($cleanedbotMessage, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $cleanedbotMessage = self::parsePossiblyEscapedJson($cleanedbotMessage);
                        if(!is_array($cleanedbotMessage)){
                            $decodedMessage = json_decode($cleanedbotMessage, true);
                        }else{
                            $decodedMessage = $cleanedbotMessage;
                        }
                        Log::info("cleanedbotMessage: $botMessage");

                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception("Invalid JSON response: $cleanedbotMessage");
                        }
                    }
                    if(!is_array($decodedMessage)){
                        $data = json_decode($decodedMessage, true);
                    }else{
                        $data = $decodedMessage;
                    }
                    if (!isset($data['description']) || !isset($data['logo_bg_color']) || !isset($data['logo_font_color']) || !isset($data['logo_border_color']) || !isset($data['abbreviation']) ) {
                        throw new \Exception("Response JSON does not contain required keys: $cleanedbotMessage");
                    }
                    $this->insurance->description = $data['description'];
                    $this->insurance->color = $data['logo_bg_color'];
                    $style = $this->insurance->style ?? [];
                    $style['bg_color'] = $data['logo_bg_color'];
                    $style['font_color'] = $data['logo_font_color'];
                    $style['border_color'] = $data['logo_border_color'];
                    $this->insurance->style = $style;
                    $this->insurance->initials = $data['abbreviation'];
                    $this->insurance->saveQuietly();
                    Log::info("Insurance updated successfully: " . $this->insurance->name);
                    // Job erfolgreich abgeschlossen, keine weiteren Versuche nötig
                    $isLoading = false;
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Attempt $attempt failed: " . $e->getMessage(), [
                    'exception' => $e,
                    'attempt' => $attempt + 1
                ]);
            }
        }

    }
}
