<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\Models\Setting;

class AiConnectionController extends Controller
{

    static function generateInsuranceDetailEvaluation($RequestData)
    {
        Log::info('generateInsuranceDetailEvaluation Run');

        $apiUrl = Setting::getValue('ai-scoring-settings', 'api_url');
        $apiKey = Setting::getValue('ai-scoring-settings', 'api_key');
        $aiModel = Setting::getValue('ai-scoring-settings', 'ai_model');
        $modelTitle = Setting::getValue('ai-scoring-settings', 'model_title');
        $refererUrl = Setting::getValue('ai-scoring-settings', 'referer_url');

        $trainContent = $RequestData['trainContent'];
        $reviews = json_encode($RequestData['data']); // neue Struktur
        $possibleTags = $RequestData['possibleTags'];

        $maxRetries = 3;
        $aiScores = [
            'average_fairness' => null,
            'average_regulation_speed' => null,
            'average_customer_service' => null,
            'average_transparency' => null,
            'tags' => '',
            'aiResultComment' => '',
        ];

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
                                possibleTags: {$possibleTags}
                                reviews: {$reviews}
                                TEXT
                        ]
                    ]
                ]);

                Log::info($response->json());

                if ($response->failed()) {
                    throw new \Exception("HTTP Error: " . $response->status());
                }

                $botMessage = $response->json()['choices'][0]['message']['content'] ?? '';
                if (!$botMessage) {
                    throw new \Exception("No content in AI response.");
                }

                Log::info("Raw Message: $botMessage");
                $cleaned = preg_replace('/^```(json)?|```$/m', '', trim($botMessage));
                $decoded = json_decode($cleaned, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $cleaned = self::parsePossiblyEscapedJson($cleaned);
                    $decoded = is_array($cleaned) ? $cleaned : json_decode($cleaned, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception("JSON parse error: $cleaned");
                    }
                }

                if (!isset($decoded['average_fairness'], $decoded['average_regulation_speed'], $decoded['average_customer_service'], $decoded['average_transparency'], $decoded['comment'], $decoded['tags'])) {
                    throw new \Exception("Missing keys in AI response: " . json_encode($decoded));
                }

                return [
                    'average_fairness' => floatval($decoded['average_fairness']),
                    'average_regulation_speed' => floatval($decoded['average_regulation_speed']),
                    'average_customer_service' => floatval($decoded['average_customer_service']),
                    'average_transparency' => floatval($decoded['average_transparency']),
                    'tags' => $decoded['tags'],
                    'aiResultComment' => preg_replace('/[\p{Han}\p{Hiragana}\p{Katakana}\p{Thai}]/u', '', $decoded['comment']),
                ];
            } catch (\Exception $e) {
                Log::error("Attempt $attempt failed: " . $e->getMessage(), [
                    'exception' => $e,
                    'attempt' => $attempt + 1
                ]);
            }
        }

        return $aiScores; // falls alles fehlschlägt
    }

    static function getAnswerSingleTextQuestion($RequestData){
        Log::info('getAnswerSingleTextQuestion Run');
        $apiUrl = Setting::getValue('ai-scoring-settings', 'api_url');
        $apiKey = Setting::getValue('ai-scoring-settings', 'api_key');
        $aiModel = Setting::getValue('ai-scoring-settings', 'ai_model');
        $modelTitle = Setting::getValue('ai-scoring-settings', 'model_title');
        $refererUrl = Setting::getValue('ai-scoring-settings', 'referer_url');
        $questionTitle = $RequestData['questionTitle'];
        $questionText = $RequestData['questionText'];
        $customerAnswer = $RequestData['customerAnswer'];
        $trainContent = $RequestData['trainContent'];
        $isLoading = true;
        $maxRetries = 3;
        $botMessage = '';
        $aiResult = '';
        $aiResultComment = '';

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
                                    Fragetitel: {$questionTitle}
                                    Fragetext: {$questionText}
                                    Antwort: {$customerAnswer}
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
                        if (!isset($data['score']) || !isset($data['comment'])) {
                            throw new \Exception("Response JSON does not contain required keys: $cleanedbotMessage");
                        }
                        $aiResult = floatval($data['score']);
                        $aiResultComment = preg_replace('/[\p{Han}\p{Hiragana}\p{Katakana}\p{Thai}]/u', '',$data['comment']);
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
        return ['score' => $aiResult, 'comment' => $aiResultComment];
    }

    static function getOverAllScore($RequestData){
        $apiUrl = Setting::getValue('ai-scoring-settings', 'api_url');
        $apiKey = Setting::getValue('ai-scoring-settings', 'api_key');
        $aiModel = Setting::getValue('ai-scoring-settings', 'ai_model');
        $modelTitle = Setting::getValue('ai-scoring-settings', 'model_title');
        $refererUrl = Setting::getValue('ai-scoring-settings', 'referer_url');
        $answers = json_encode($RequestData['answers']);
        $attachments = json_encode($RequestData['attachments']);
        $trainContent = $RequestData['trainContent'];
        $possibleTags = $RequestData['possibleTags'];
        $isLoading = true;
        $maxRetries = 3;
        $botMessage = '';
        $aiOverAllScore = '';
        $ai_regulation_speed_Score = '';
        $ai_customer_service_Score = '';
        $ai_fairness_Score = '';
        $ai_transparency_Score = '';
        $aiResultComment = '';

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
                                    possibleTags: {$possibleTags}
                                    attachments: {$attachments}
                                    answers: {$answers}
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
                        if (!isset($data['overall_score']) || !isset($data['comment']) || !isset($data['regulation_speed']) || !isset($data['customer_service']) || !isset($data['fairness']) || !isset($data['transparency']) || !isset($data['tags'])) {
                            throw new \Exception("Response JSON does not contain required keys: $cleanedbotMessage");
                        }
                        $aiOverAllScore = floatval($data['overall_score']);
                        $ai_regulation_speed_Score = floatval($data['regulation_speed']);
                        $ai_customer_service_Score = floatval($data['customer_service']);
                        $ai_fairness_Score = floatval($data['fairness']);
                        $ai_transparency_Score = floatval($data['transparency']);
                        $aiResultTags = $data['tags'];
                        $aiResultComment = preg_replace('/[\p{Han}\p{Hiragana}\p{Katakana}\p{Thai}]/u', '',$data['comment']);

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
        return ['overall_score' => $aiOverAllScore,'regulation_speed' => $ai_regulation_speed_Score,'customer_service' => $ai_customer_service_Score,'fairness' => $ai_fairness_Score, 'transparency' => $ai_transparency_Score, 'tags' => $aiResultTags, 'aiResultComment' => $aiResultComment];
    }

    public static function parsePossiblyEscapedJson(string $raw)
    {
        // 1. Entferne führende und abschließende Anführungszeichen
        $clean = trim($raw, "\"");

        // 2. Entferne Escape-Zeichen (z. B. \" => ")
        $clean = stripslashes($clean); // oder: str_replace('\"', '"', $clean)

        // 3. Versuche zu decodieren
        $json = json_decode($clean, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Fehler beim Parsen der GPT-Antwort', [
                'input' => $raw,
                'bereinigt' => $clean,
                'fehler' => json_last_error_msg(),
            ]);
            return null;
        }

        return $json;
    }

}

