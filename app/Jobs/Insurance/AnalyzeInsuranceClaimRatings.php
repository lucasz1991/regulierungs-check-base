<?php

namespace App\Jobs\Insurance;

use App\Http\Controllers\Customer\ClaimRating\AIEvalController;
use App\Models\ClaimRating;
use App\Models\DetailInsuranceRating;
use App\Models\Insurance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AnalyzeInsuranceClaimRatings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const MIN_RATINGS = 100;

    public ?int $insuranceId;
    public ?int $insuranceSubtypeId;
    public ?int $insuranceTypeId;

    public function __construct($insuranceId = null, ?int $insuranceSubtypeId = null, ?int $insuranceTypeId = null)
    {
        if ($insuranceId instanceof Insurance) {
            $insuranceId = $insuranceId->id;
        }

        $this->insuranceId = $insuranceId ? (int) $insuranceId : null;
        $this->insuranceSubtypeId = $insuranceSubtypeId;
        $this->insuranceTypeId = $insuranceTypeId;
    }

    public function handle(): void
    {
        if ($this->insuranceId) {
            $insurance = Insurance::query()->find($this->insuranceId);
            if (! $insurance) {
                Log::warning("AnalyzeInsuranceClaimRatings: Insurance {$this->insuranceId} nicht gefunden.");
                return;
            }

            $this->analyzeSingleInsurance($insurance, $this->insuranceSubtypeId, $this->insuranceTypeId);
            return;
        }

        Insurance::query()
            ->select('id')
            ->orderBy('id')
            ->chunkById(500, function ($insurances) {
                foreach ($insurances as $insurance) {
                    $this->analyzeSingleInsurance($insurance, $this->insuranceSubtypeId, $this->insuranceTypeId);
                }
            });
    }

    protected function analyzeSingleInsurance(Insurance $insurance, ?int $onlySubtypeId = null, ?int $onlyTypeId = null): void
    {
        $q = ClaimRating::query()
            ->where('insurance_id', $insurance->id)
            ->where('status', 'rated')
            ->where(function (Builder $b) {
                $b->whereNotNull('attachments')
                    ->whereRaw("JSON_EXTRACT(attachments, '$.scorings') IS NOT NULL");
            });

        if ($onlyTypeId) {
            $q->where('insurance_type_id', $onlyTypeId);
        }

        if ($onlySubtypeId) {
            $q->where('insurance_subtype_id', $onlySubtypeId);
        }

        $count = (clone $q)->count();

        if ($count < self::MIN_RATINGS) {
            $this->upsertDetailRating($insurance->id, $onlySubtypeId, $onlyTypeId, [
                'fairness' => null,
                'speed' => null,
                'communication' => null,
                'transparency' => null,
                'total_score' => null,
                'ai_comment' => "Analyse uebersprungen: mindestens " . self::MIN_RATINGS . " Bewertungen erforderlich (aktuell: {$count}).",
                'ai_tags' => [],
                'status' => 'insufficient_data',
                'rating_count' => $count,
            ]);
            Log::info("AnalyzeInsuranceClaimRatings: Zu wenige Ratings ({$count}/" . self::MIN_RATINGS . ") fuer Insurance #{$insurance->id} " . $this->scopeLabel($onlyTypeId, $onlySubtypeId));
            return;
        }

        $reviews = [];
        $q->orderBy('id')->chunkById(1000, function ($ratings) use (&$reviews) {
            foreach ($ratings as $rating) {
                $scoring = Arr::get($rating->attachments, 'scorings', []);
                $comment = Arr::get($rating->attachments, 'ai_overall_comment')
                    ?? Arr::get($rating->answers, 'Kommentar')
                    ?? Arr::get($rating->answers, 'comment')
                    ?? '';

                $reviews[] = [
                    'fairness' => self::fnum(Arr::get($scoring, 'fairness')),
                    'regulation_speed' => self::fnum(Arr::get($scoring, 'regulation_speed')),
                    'customer_service' => self::fnum(Arr::get($scoring, 'customer_service')),
                    'transparency' => self::fnum(Arr::get($scoring, 'transparency')),
                    'insurance_type_id' => $rating->insurance_type_id,
                    'insurance_subtype_id' => $rating->insurance_subtype_id,
                    'text' => is_string($comment) ? $comment : json_encode($comment, JSON_UNESCAPED_UNICODE),
                ];
            }
        });

        if (empty($reviews)) {
            $this->upsertDetailRating($insurance->id, $onlySubtypeId, $onlyTypeId, [
                'fairness' => null,
                'speed' => null,
                'communication' => null,
                'transparency' => null,
                'total_score' => null,
                'ai_comment' => null,
                'ai_tags' => [],
                'status' => 'pending',
                'rating_count' => $count,
            ]);
            return;
        }

        $payload = [
            'scope' => [
                'insurance_type_id' => $onlyTypeId,
                'insurance_subtype_id' => $onlySubtypeId,
            ],
            'reviews' => $reviews,
        ];

        try {
            $response = AIEvalController::getInsuranceDetailEvaluation($payload);

            $avgFairness = self::fnum(Arr::get($response, 'average_fairness'));
            $avgSpeed = self::fnum(Arr::get($response, 'average_regulation_speed'));
            $avgCustomer = self::fnum(Arr::get($response, 'average_customer_service'));
            $avgTransparency = self::fnum(Arr::get($response, 'average_transparency'));
            $comment = Arr::get($response, 'comment');

            $tagsRaw = Arr::get($response, 'tags', []);
            $tags = is_string($tagsRaw)
                ? array_values(array_filter(array_map(static fn ($tagId) => is_numeric($tagId) ? (int) $tagId : null, explode(',', $tagsRaw))))
                : (array) $tagsRaw;

            $total = self::avgNullable([$avgFairness, $avgSpeed, $avgCustomer, $avgTransparency]);

            $this->upsertDetailRating($insurance->id, $onlySubtypeId, $onlyTypeId, [
                'fairness' => $avgFairness,
                'speed' => $avgSpeed,
                'communication' => $avgCustomer,
                'transparency' => $avgTransparency,
                'total_score' => $total,
                'ai_comment' => $comment,
                'ai_tags' => array_slice($tags, 0, 3),
                'status' => 'approved',
                'rating_count' => $count,
            ]);

            Log::info("AnalyzeInsuranceClaimRatings: Detail gespeichert fuer Insurance #{$insurance->id} " . $this->scopeLabel($onlyTypeId, $onlySubtypeId));
        } catch (\Throwable $e) {
            Log::error("AnalyzeInsuranceClaimRatings: KI-Call fehlgeschlagen: {$e->getMessage()}");
        }
    }

    protected function upsertDetailRating(int $insuranceId, ?int $subtypeId, ?int $typeId, array $data): void
    {
        DetailInsuranceRating::updateOrCreate(
            [
                'insurance_id' => $insuranceId,
                'insurance_type_id' => $typeId,
                'insurance_subtype_id' => $subtypeId,
            ],
            [
                'type' => $this->detailType($typeId, $subtypeId),
                'status' => $data['status'] ?? 'pending',
                'fairness' => $data['fairness'] ?? null,
                'speed' => $data['speed'] ?? null,
                'communication' => $data['communication'] ?? null,
                'transparency' => $data['transparency'] ?? null,
                'total_score' => $data['total_score'] ?? null,
                'ai_comment' => $data['ai_comment'] ?? null,
                'ai_tags' => $data['ai_tags'] ?? [],
            ]
        );
    }

    protected static function fnum($value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    protected static function avgNullable(array $values): ?float
    {
        $numbers = array_values(array_filter($values, static fn ($value) => is_numeric($value)));

        if (count($numbers) === 0) {
            return null;
        }

        return round(array_sum($numbers) / count($numbers), 3);
    }

    private function detailType(?int $typeId, ?int $subtypeId): string
    {
        if ($typeId && $subtypeId) {
            return 'type_subtyp';
        }

        if ($typeId) {
            return 'type';
        }

        return $subtypeId ? 'subtyp' : 'overall';
    }

    private function scopeLabel(?int $typeId, ?int $subtypeId): string
    {
        $parts = [];

        if ($typeId) {
            $parts[] = "Type #{$typeId}";
        }

        if ($subtypeId) {
            $parts[] = "Subtype #{$subtypeId}";
        }

        return empty($parts) ? '(Overall)' : '(' . implode(' / ', $parts) . ')';
    }
}
