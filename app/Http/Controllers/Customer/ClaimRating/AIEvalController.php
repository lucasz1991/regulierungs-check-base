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
        $this->apiUrl = Setting::getValue('ai-scoring-settings', 'api_url');
        $this->apiKey = Setting::getValue('ai-scoring-settings', 'api_key');
        $this->aiModel = Setting::getValue('ai-scoring-settings', 'ai_model');
        $this->modelTitle = Setting::getValue('ai-scoring-settings', 'model_title');
        $this->refererUrl = Setting::getValue('ai-scoring-settings', 'referer_url');
    }

    static function getScoreForTextarea($qustion, $answer)
    {
        $this->trainContent = '';

        Log::info('Evaluating score for textarea', [
            'question' => $qustion,
            'answer' => $answer,
        ]);
        return 0.333;
    }
    static function getScoreForRatingSpeed($qustion, $answer)
    {
        Log::info('Evaluating score for textarea', [
            'question' => $qustion,
            'answer' => $answer,
        ]);
        return 0.333;
    }
}
