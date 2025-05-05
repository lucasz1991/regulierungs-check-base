<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClaimRating;
use App\Models\Insurance;
use App\Models\InsuranceType;
use App\Models\InsuranceSubtype;
use App\Models\RatingQuestionnaireVersion;
use App\Models\Setting;
use App\Models\RatingQuestion;
use App\Models\RatingQuestionnaire; 



class ClaimRatingController extends Controller
{
    public function evaluateScore(ClaimRating $claimRating)
    {
        // Versicherungstyp abrufen
        $subtype = $claimRating->insuranceSubtype;
    
        if (!$subtype || !isset($subtype->average_rating_speed)) {
            return response()->json([
                'success' => false,
                'message' => 'Kein Standardwert für Regulierungsdauer hinterlegt.',
            ], 422);
        }
    
        // Regulierungstage aus den Antworten extrahieren
        $answers = $claimRating->answers;
        $actualDays = $answers['regulation_days'] ?? null;
    
        if ($actualDays === null) {
            return response()->json([
                'success' => false,
                'message' => 'Keine tatsächliche Regulierungsdauer angegeben.',
            ], 422);
        }
    
        $standardDays = $subtype->default_regulation_days;
        $difference = $actualDays - $standardDays;
    
        // Score-Berechnung: je weniger Abweichung, desto besser
        if ($difference <= 0) {
            $score = 5; // schneller oder pünktlich
        } elseif ($difference <= 3) {
            $score = 4;
        } elseif ($difference <= 7) {
            $score = 3;
        } elseif ($difference <= 14) {
            $score = 2;
        } else {
            $score = 1;
        }
    
        // Speichern
        $claimRating->rating_score = $score;
        $claimRating->save();
    
        return response()->json([
            'success' => true,
            'rating_score' => $score,
            'standard_days' => $standardDays,
            'actual_days' => $actualDays,
            'difference' => $difference,
        ]);
    }
    

}
