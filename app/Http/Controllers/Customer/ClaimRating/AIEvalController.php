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
    static function getScoreForTextarea($qustion, $answer)
    {
        Log::info('Evaluating score for textarea', [
            'question' => $qustion,
            'answer' => $answer,
        ]);
        return 0.333;
    }
}
