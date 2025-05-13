<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


use App\Models\ClaimRating;
use App\Models\Insurance;
use App\Models\InsuranceType;
use App\Models\InsuranceSubtype;
use App\Models\RatingQuestionnaireVersion;
use App\Models\Setting;
use App\Models\RatingQuestion;
use App\Models\RatingQuestionnaire; 

class ClaimRatingAIEval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ClaimRating $claimRating;

    /**
     * Create a new job instance.
     */
    public function __construct(ClaimRating $claimRating)
    {
        $this->claimRating = $claimRating;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {        
        // Versicherungstyp abrufen
        $subtype = $this->claimRating->insuranceSubtype;
        $insuranceSubtype_average_rating_speed = $subtype->average_rating_speed ?? 30;
    
        // Regulierungstage aus den Antworten extrahieren
        $answers = $this->claimRating->answers;
        if (isset($answers['selectedDates']['ended_at'])) {
            $actualDays = (new \DateTime($answers['selectedDates']['started_at']))->diff(new \DateTime($answers['selectedDates']['ended_at']))->days;
        } else {
            $actualDays = (new \DateTime($answers['selectedDates']['started_at']))->diff(new \DateTime())->days;
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
        // Berechnung des Scores für Variable Fragen 
        
        $questionnaireVersion = $this->claimRating->questionnaireVersion()->first();
        $questionnaireVersionSnapshot = $questionnaireVersion->snapshot;
        $variableQuestionScore = 0;
        foreach ($questionnaireVersionSnapshot as $snapshotQuestion) {
            $score = $this->calculateScore($snapshotQuestion);
            $variableQuestionScore += $score * $snapshotQuestion['pivot']['weight'];
        }
        $variableQuestionScore = $variableQuestionScore / count($questionnaireVersionSnapshot);

        // Kombinieren der Scores mit den entsprechenden Gewichtungen
        $score = ($rating_speed_score * 0.7) + ($variableQuestionScore * 0.3);

        // Speichern
        $this->claimRating->rating_score = $score;
        $this->claimRating->saveQuietly();
    
        Log::info("AI-Evaluation completed for ClaimRating ID ".$this->claimRating->id);

    }


    /**
     * Calculate the score based on the type of question and its value.
     *
     * @param array $snapshotQuestion
     * @return float
     */
    public function calculateScore(ClaimRating $claimRating , $snapshotQuestion){
        $value = $this->claimRating->answers[$snapshotQuestion['title']] ?? null;
        switch ($snapshotQuestion['type']) {
            case 'rating':
                return $value / 5;
            case 'textarea':
                return strlen($value) > 3 ? 0.5 : 1;
            default:
                return 0;
        }
    }
}
