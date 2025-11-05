<?php

namespace App\Jobs\Insurance;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

use App\Models\Insurance;
use App\Models\ClaimRating;
use App\Models\DetailInsuranceRating;
use App\Http\Controllers\Customer\ClaimRating\AIEvalController;

class AnalyzeInsuranceClaimRatings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const MIN_RATINGS = 100; // <- Mindestanzahl

    public ?int $insuranceId;
    public ?int $insuranceSubtypeId;

    public function __construct(?int $insuranceId = null, ?int $insuranceSubtypeId = null)
    {
        $this->insuranceId        = $insuranceId;
        $this->insuranceSubtypeId = $insuranceSubtypeId;
    }

    public function handle(): void
    {
        if ($this->insuranceId) {
            $insurance = Insurance::query()->find($this->insuranceId);
            if (! $insurance) {
                Log::warning("AnalyzeInsuranceClaimRatings: Insurance {$this->insuranceId} nicht gefunden.");
                return;
            }
            $this->analyzeSingleInsurance($insurance, $this->insuranceSubtypeId);
            return;
        }

        Insurance::query()
            ->select('id')
            ->orderBy('id')
            ->chunkById(500, function ($insurances) {
                foreach ($insurances as $insurance) {
                    $this->analyzeSingleInsurance($insurance, $this->insuranceSubtypeId);
                }
            });
    }

    protected function analyzeSingleInsurance(Insurance $insurance, ?int $onlySubtypeId = null): void
    {
        $q = ClaimRating::query()
            ->where('insurance_id', $insurance->id)
            ->where('status', 'rated')
            ->where(function (Builder $b) {
                $b->whereNotNull('attachments')
                  ->whereRaw("JSON_EXTRACT(attachments, '$.scorings') IS NOT NULL");
            });

        if ($onlySubtypeId) {
            $q->where('insurance_subtype_id', $onlySubtypeId);
        }

        $count = (clone $q)->count();

        // >>> NEU: Mindestanzahl prüfen
        if ($count < self::MIN_RATINGS) {
            $this->upsertDetailRating($insurance->id, $onlySubtypeId, [
                'fairness'      => null,
                'speed'         => null,
                'communication' => null,
                'transparency'  => null,
                'total_score'   => null,
                'ai_comment'    => "Analyse übersprungen: mindestens ".self::MIN_RATINGS." Bewertungen erforderlich (aktuell: {$count}).",
                'ai_tags'       => [],
                'status'        => 'insufficient_data',
                'rating_count'  => $count,
            ]);
            Log::info("AnalyzeInsuranceClaimRatings: Zu wenige Ratings ({$count}/".self::MIN_RATINGS.") für Insurance #{$insurance->id}" . ($onlySubtypeId ? " (Subtype #{$onlySubtypeId})" : ''));
            return;
        }
        // <<< NEU Ende

        // Reviews für KI aufbauen
        $reviews = [];
        $q->orderBy('id')->chunkById(1000, function ($ratings) use (&$reviews) {
            foreach ($ratings as $r) {
                $sc = Arr::get($r->attachments, 'scorings', []);
                $comment = Arr::get($r->attachments, 'ai_overall_comment')
                          ?? Arr::get($r->answers, 'Kommentar')
                          ?? Arr::get($r->answers, 'comment')
                          ?? '';

                $reviews[] = [
                    'fairness'          => self::fnum(Arr::get($sc, 'fairness')),
                    'regulation_speed'  => self::fnum(Arr::get($sc, 'regulation_speed')),
                    'customer_service'  => self::fnum(Arr::get($sc, 'customer_service')),
                    'transparency'      => self::fnum(Arr::get($sc, 'transparency')),
                    'text'              => is_string($comment) ? $comment : json_encode($comment, JSON_UNESCAPED_UNICODE),
                ];
            }
        });

        if (empty($reviews)) {
            $this->upsertDetailRating($insurance->id, $onlySubtypeId, [
                'fairness'      => null,
                'speed'         => null,
                'communication' => null,
                'transparency'  => null,
                'total_score'   => null,
                'ai_comment'    => null,
                'ai_tags'       => [],
                'status'        => 'pending',
                'rating_count'  => $count,
            ]);
            return;
        }

        // KI-Aufruf
        $payload = ['reviews' => $reviews];
        try {
            $resp     = AIEvalController::getInsuranceDetailEvaluation($payload);

            $avgFair  = self::fnum(Arr::get($resp, 'average_fairness'));
            $avgSpeed = self::fnum(Arr::get($resp, 'average_regulation_speed'));
            $avgCust  = self::fnum(Arr::get($resp, 'average_customer_service'));
            $avgTrans = self::fnum(Arr::get($resp, 'average_transparency'));
            $comment  = Arr::get($resp, 'comment');

            $tagsRaw = Arr::get($resp, 'tags', []);
            $tags = is_string($tagsRaw)
                ? array_values(array_filter(array_map(static fn($x) => is_numeric($x) ? (int)$x : null, explode(',', $tagsRaw))))
                : (array) $tagsRaw;

            $total = self::avgNullable([$avgFair, $avgSpeed, $avgCust, $avgTrans]);

            $this->upsertDetailRating($insurance->id, $onlySubtypeId, [
                'fairness'      => $avgFair,
                'speed'         => $avgSpeed,
                'communication' => $avgCust,
                'transparency'  => $avgTrans,
                'total_score'   => $total,
                'ai_comment'    => $comment,
                'ai_tags'       => array_slice($tags, 0, 3),
                'status'        => 'approved',
                'rating_count'  => $count,
            ]);

            Log::info("AnalyzeInsuranceClaimRatings: Detail gespeichert für Insurance #{$insurance->id}" . ($onlySubtypeId ? " (Subtype #{$onlySubtypeId})" : ''));

        } catch (\Throwable $e) {
            Log::error("AnalyzeInsuranceClaimRatings: KI-Call fehlgeschlagen: {$e->getMessage()}");
        }
    }

    protected function upsertDetailRating(int $insuranceId, ?int $subtypeId, array $data): void
    {
        DetailInsuranceRating::updateOrCreate(
            [
                'insurance_id'         => $insuranceId,
                'insurance_subtype_id' => $subtypeId,
                'type'                 => 'ai',
            ],
            [
                'status'        => $data['status']        ?? 'pending',
                'fairness'      => $data['fairness']      ?? null,
                'speed'         => $data['speed']         ?? null,
                'communication' => $data['communication'] ?? null,
                'transparency'  => $data['transparency']  ?? null,
                'total_score'   => $data['total_score']   ?? null,
                'ai_comment'    => $data['ai_comment']    ?? null,
                'ai_tags'       => $data['ai_tags']       ?? [],
                // optional: Rating-Anzahl mitschreiben (falls du eine Spalte hast; sonst in ai_comment/stats dokumentieren)
                // 'rating_count' => $data['rating_count'] ?? null,
            ]
        );
    }

    protected static function fnum($v): ?float
    {
        return is_numeric($v) ? (float)$v : null;
    }

    protected static function avgNullable(array $vals): ?float
    {
        $nums = array_values(array_filter($vals, static fn($v) => is_numeric($v)));
        if (count($nums) === 0) return null;
        return round(array_sum($nums) / count($nums), 3);
    }
}
