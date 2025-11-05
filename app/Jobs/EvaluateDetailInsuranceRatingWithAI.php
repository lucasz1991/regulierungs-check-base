<?php

namespace App\Jobs;

use App\Models\Insurance;
use App\Models\ClaimRating;
use App\Models\DetailInsuranceRating;
use App\Models\RatingTag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Customer\ClaimRating\AIEvalController;

class EvaluateDetailInsuranceRatingWithAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Insurance $insurance;
    public ?int $subtypeId;

    /**
     * Create a new job instance.
     */
    public function __construct(Insurance $insurance, ?int $subtypeId = null)
    {
        $this->insurance = $insurance;
        $this->subtypeId = $subtypeId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $query = ClaimRating::where('insurance_id', $this->insurance->id)
            ->where('status', 'rated');

        if ($this->subtypeId) {
            $query->where('insurance_subtype_id', $this->subtypeId);
        } else {
            $query->whereNull('insurance_subtype_id');
        }

        $ratings = $query->get();

        if ($ratings->count() < 2) {
            Log::info("Zu wenige Bewertungen für AI-Auswertung: {$this->insurance->name} " . ($this->subtypeId ? "/ Subtype ID: {$this->subtypeId}" : "(Allgemein)"));
            return;
        }

        $subtypeName = $this->subtypeId
            ? optional($this->insurance->subtypes->firstWhere('id', $this->subtypeId))->name
            : 'Allgemein';

        // Vollständige Bewertungsdaten sammeln
        $ratingData = $ratings->map(function ($r) use ($subtypeName) {
            return [
                'id' => $r->id,
                'rating_score' => $r->rating_score,
                'answers' => $r->answers,
                'attachments' => $r->attachments,
                'tag_ids' => $r->tag_ids,
                'created_at' => $r->created_at->toDateTimeString(),
                'subtype_name' => $subtypeName,
            ];
        })->toArray();

        // Alle möglichen Tags laden
        $possibleTags = RatingTag::get()->toArray();

        // KI-Auswertung durchführen
        $evaluation = AIEvalController::getInsuranceDetailEvaluation([
            'data' => $ratingData,
            'possibleTags' => json_encode($possibleTags),
        ]);

        // Durchschnittsscore berechnen
        $totalScore = round((
            $evaluation['average_fairness'] +
            $evaluation['average_regulation_speed'] +
            $evaluation['average_customer_service'] +
            $evaluation['average_transparency']
        ) / 4, 2);

        // Detail-Rating speichern
        DetailInsuranceRating::updateOrCreate(
            [
                'insurance_id' => $this->insurance->id,
                'insurance_subtype_id' => $this->subtypeId,
            ],
            [
                'type' => $this->subtypeId ? 'subtyp' : 'overall',
                'status' => 'evaluated',
                'fairness' => $evaluation['average_fairness'],
                'speed' => $evaluation['average_regulation_speed'],
                'communication' => $evaluation['average_customer_service'],
                'transparency' => $evaluation['average_transparency'],
                'total_score' => $totalScore,
                'ai_comment' => $evaluation['comment'],
                'admin_comment' => null,
            ]
        );

        Log::info("KI-Auswertung abgeschlossen für: {$this->insurance->name}" . ($this->subtypeId ? " / {$subtypeName}" : " (Allgemein)"));
    }
}
