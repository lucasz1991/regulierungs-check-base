<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;


class ChatStreamController extends Controller
{
    public function store(Request $request)
    {
        Session::put('stream_message', $request->message);
        return response()->json(['status' => 'saved']);
    }

    public function stream()
    {
        $status = Setting::getValue('ai_assistant', 'status');
        $assistantName = Setting::getValue('ai_assistant', 'assistant_name');
        $message = Session::get('stream_message', '');
        
        return response()->stream(function () use ($message) {
            try {
            $apiUrl = Setting::getValue('ai_assistant', 'api_url');
            $apiKey = Setting::getValue('ai_assistant', 'api_key');
            $aiModel = Setting::getValue('ai_assistant', 'ai_model');
            $modelTitle = Setting::getValue('ai_assistant', 'model_title');
            $refererUrl = Setting::getValue('ai_assistant', 'referer_url');
            $trainContent = Setting::getValue('ai_assistant', 'train_content');
            $client = new \GuzzleHttp\Client();
            Log::info('Guzzle Client Created', [
                'client_config' => $client->getConfig(),
            ]);
            Log::info('AI API Request', [
                'apiUrl' => $apiUrl,
                'aiModel' => $aiModel,
                'modelTitle' => $modelTitle,
                'refererUrl' => $refererUrl,
                'userMessage' => $message,
            ]);
            $response = $client->post($apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'text/event-stream',
                ],
                'stream' => true,
                'body' => json_encode([
                    'model' => $aiModel,
                    'stream' => true,
                    'messages' => [
                        ['role' => 'system', 'content' => trim(preg_replace('/\s+/', ' ', $trainContent))],
                        ['role' => 'user', 'content' => $message],
                    ]
                ])
            ]);

            foreach ($response->getBody() as $line) {
                if (str_starts_with($line, "data: ")) {
                    $data = substr($line, 6);
                    if (trim($data) === "[DONE]") {
                        echo "data: [DONE]\n\n";
                        break;
                    }
                    echo "data: {$data}\n\n";
                    ob_flush(); flush();
                }
            }
            } catch (\Throwable $e) {
            echo "data: [ERROR] " . $e->getMessage() . "\n\n";
            ob_flush(); flush();
            \Log::error('STREAM ERROR: ' . $e->getMessage());
        }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
}
