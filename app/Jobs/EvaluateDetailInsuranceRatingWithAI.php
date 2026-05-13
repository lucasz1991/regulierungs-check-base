<?php

namespace App\Jobs;

use App\Http\Controllers\Customer\ClaimRating\AIEvalController;
use App\Models\ClaimRating;
use App\Models\DetailInsuranceRating;
use App\Models\Insurance;
use App\Models\InsuranceSubtype;
use App\Models\InsuranceType;
use App\Models\RatingTag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EvaluateDetailInsuranceRatingWithAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Insurance $insurance;
    public ?int $subtypeId;
    public ?int $typeId;

    public function __construct(Insurance $insurance, ?int $subtypeId = null, ?int $typeId = null)
    {
        $this->insurance = $insurance;
        $this->subtypeId = $subtypeId;
        $this->typeId = $typeId;
    }

    public function handle(): void
    {
        $query = ClaimRating::where('insurance_id', $this->insurance->id)
            ->where('status', 'rated');

        if ($this->typeId) {
            $query->where('insurance_type_id', $this->typeId);
        }

        if ($this->subtypeId) {
            $query->where('insurance_subtype_id', $this->subtypeId);
        }

        $ratings = $query->get();

        if ($ratings->count() < 2) {
            Log::info("Zu wenige Bewertungen fuer AI-Auswertung: {$this->insurance->name} " . $this->scopeLabel());
            return;
        }

        $typeName = $this->typeId
            ? InsuranceType::query()->whereKey($this->typeId)->value('name')
            : 'Alle Versicherungsarten';

        $subtypeName = $this->subtypeId
            ? InsuranceSubtype::query()->whereKey($this->subtypeId)->value('name')
            : 'Alle Subtypen';

        $ratingData = $ratings->map(function (ClaimRating $rating) use ($typeName, $subtypeName) {
            return [
                'id' => $rating->id,
                'rating_score' => $rating->rating_score,
                'answers' => $rating->answers,
                'attachments' => $rating->attachments,
                'tag_ids' => $rating->tag_ids,
                'created_at' => $rating->created_at->toDateTimeString(),
                'insurance_type_id' => $rating->insurance_type_id,
                'type_name' => $typeName,
                'insurance_subtype_id' => $rating->insurance_subtype_id,
                'subtype_name' => $subtypeName,
            ];
        })->toArray();

        $possibleTags = RatingTag::get()->toArray();

        $evaluation = AIEvalController::getInsuranceDetailEvaluation([
            'scope' => [
                'insurance_type_id' => $this->typeId,
                'type_name' => $typeName,
                'insurance_subtype_id' => $this->subtypeId,
                'subtype_name' => $subtypeName,
            ],
            'data' => $ratingData,
            'possibleTags' => json_encode($possibleTags),
        ]);

        $totalScore = round((
            $evaluation['average_fairness'] +
            $evaluation['average_regulation_speed'] +
            $evaluation['average_customer_service'] +
            $evaluation['average_transparency']
        ) / 4, 2);

        DetailInsuranceRating::updateOrCreate(
            [
                'insurance_id' => $this->insurance->id,
                'insurance_type_id' => $this->typeId,
                'insurance_subtype_id' => $this->subtypeId,
            ],
            [
                'type' => $this->detailType(),
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

        Log::info("KI-Auswertung abgeschlossen fuer: {$this->insurance->name} " . $this->scopeLabel($typeName, $subtypeName));
    }

    private function detailType(): string
    {
        if ($this->typeId && $this->subtypeId) {
            return 'type_subtyp';
        }

        if ($this->typeId) {
            return 'type';
        }

        return $this->subtypeId ? 'subtyp' : 'overall';
    }

    private function scopeLabel(?string $typeName = null, ?string $subtypeName = null): string
    {
        $parts = [];

        if ($this->typeId) {
            $parts[] = 'Type: ' . ($typeName ?: "#{$this->typeId}");
        }

        if ($this->subtypeId) {
            $parts[] = 'Subtype: ' . ($subtypeName ?: "#{$this->subtypeId}");
        }

        return empty($parts) ? '(Overall)' : '(' . implode(' / ', $parts) . ')';
    }
}
