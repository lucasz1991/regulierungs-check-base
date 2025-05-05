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
        $insuranceSubtype_average_rating_speed = $subtype->average_rating_speed ?? 30;
    
        // Regulierungstage aus den Antworten extrahieren
        $answers = $claimRating->answers;
        if (isset($answers['ended_at'])) {
            $actualDays = (new \DateTime($answers['started_at']))->diff(new \DateTime($answers['ended_at']))->days;
        } else {
            $actualDays = (new \DateTime($answers['started_at']))->diff(new \DateTime())->days;
        }
        // Überprüfen, ob $actualDays größer ist als $insuranceSubtype_average_rating_speed
        // Wenn ja, dann berechnen Sie den Unterschied
        // Wenn nein, dann setzen Sie den Unterschied auf 0

        if ($actualDays > $insuranceSubtype_average_rating_speed) {
            $difference = $actualDays - $insuranceSubtype_average_rating_speed;
            $rating_speed_score = max(0, 0.99 - ($difference / $insuranceSubtype_average_rating_speed));
        } else {
            $difference = 0;
            $rating_speed_score = 1;
        }
    
        // Berechnung des Scores
    
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
