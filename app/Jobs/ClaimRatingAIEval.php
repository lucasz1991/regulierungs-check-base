<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


use App\Http\Controllers\Customer\ClaimRating\AIEvalController;


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
        $attachments = $this->claimRating->attachments ?? [];
        $attachments['eval_details']['insuranceSubtype_average_rating_speed'] = $insuranceSubtype_average_rating_speed;
        // Regulierungstage aus den Antworten extrahieren
        $answers = $this->claimRating->answers;
        if (isset($answers['selectedDates']['ended_at'])) {
            $actualDays = (new \DateTime($answers['selectedDates']['started_at']))->diff(new \DateTime($answers['selectedDates']['ended_at']))->days;
        } else {
            $actualDays = (new \DateTime($answers['selectedDates']['started_at']))->diff(new \DateTime())->days;
        }
        $attachments['eval_details']['actualDays'] = $actualDays;
        

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
        $attachments['eval_details']['days_difference'] = $difference;
       

        $questionnaireVersion = $this->claimRating->questionnaireVersion()->first();
        $questionnaireVersionSnapshot = $questionnaireVersion->snapshot;
        $variableQuestionScore = 0;
        $variableQuestionCount = 0;
        foreach ($questionnaireVersionSnapshot as $snapshotQuestion) {
            $calculatedScore = $this->calculateScore($snapshotQuestion);
            if($calculatedScore != -1){
                $attachments['scorings'][$snapshotQuestion['id']] = [
                    'question_title' => $snapshotQuestion['title'],
                    'question_weight' => $snapshotQuestion['pivot']['weight'],
                    'ai_score' => $calculatedScore,
                ];
                $variableQuestionScore += $calculatedScore * $snapshotQuestion['pivot']['weight'];
                $variableQuestionCount++;
            }
        }
        $variableQuestionScore = $variableQuestionScore / $variableQuestionCount;

        $attachments['scorings']['variable_questions'] = $variableQuestionScore;
        $attachments['scorings']['regulation_speed'] = $variableQuestionScore;

        // Kombinieren der Scores mit den entsprechenden Gewichtungen
        $calculatedScore = ($rating_speed_score * 0.7) + ($variableQuestionScore * 0.3);


        // Speichern
        $this->claimRating->attachments = $attachments;
        $this->claimRating->rating_score = $calculatedScore;
        $this->claimRating->saveQuietly();
    
        Log::info("AI-Evaluation completed for ClaimRating ID ".$this->claimRating->id);

    }


    /**
     * Calculate the score based on the type of question and its value.
     *
     * @param array $snapshotQuestion
     * @return float
     */
    public function calculateScore($snapshotQuestion){
        $value = $this->claimRating->answers[$snapshotQuestion['title']] ?? null;
        switch ($snapshotQuestion['type']) {
            case 'rating':
                return $value / 5;
            case 'textarea':
                if(strlen($value) > 3){
                    return AIEvalController::getScoreForTextarea($snapshotQuestion, $value);
                }else{
                    return -1;
                }
                
            default:
                return -1;
        }
    }
}
