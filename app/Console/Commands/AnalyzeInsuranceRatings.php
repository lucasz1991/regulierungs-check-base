<?php

namespace App\Console\Commands;

use App\Jobs\EvaluateDetailInsuranceRatingWithAI;
use App\Models\ClaimRating;
use App\Models\Insurance;
use Illuminate\Console\Command;

class AnalyzeInsuranceRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ratings:analyze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analysiert Versicherungen, Versicherungsarten und Subtypen mit ausreichend ClaimRatings und startet die KI-Detailauswertung.';

    /**
     * Mindestanzahl an Bewertungen.
     *
     * @var int
     */
    protected int $MIN_COUNT = 100;

    public function handle(): int
    {
        $min = $this->MIN_COUNT;

        $this->info("Starte KI-Analyse aller Versicherungen (Mindestanzahl: {$min} Bewertungen)...");

        $countsPerInsurance = ClaimRating::query()
            ->selectRaw('insurance_id, COUNT(*) as cnt')
            ->where('status', 'rated')
            ->groupBy('insurance_id')
            ->pluck('cnt', 'insurance_id');

        if ($countsPerInsurance->isEmpty()) {
            $this->warn('Keine ClaimRatings mit Status "rated" gefunden.');
            return Command::SUCCESS;
        }

        $countsPerType = ClaimRating::query()
            ->selectRaw('insurance_id, insurance_type_id, COUNT(*) as cnt')
            ->where('status', 'rated')
            ->whereNotNull('insurance_type_id')
            ->groupBy('insurance_id', 'insurance_type_id')
            ->get()
            ->groupBy('insurance_id');

        $countsPerSubtype = ClaimRating::query()
            ->selectRaw('insurance_id, insurance_subtype_id, COUNT(*) as cnt')
            ->where('status', 'rated')
            ->whereNotNull('insurance_subtype_id')
            ->groupBy('insurance_id', 'insurance_subtype_id')
            ->get()
            ->groupBy('insurance_id');

        $countsPerTypeAndSubtype = ClaimRating::query()
            ->selectRaw('insurance_id, insurance_type_id, insurance_subtype_id, COUNT(*) as cnt')
            ->where('status', 'rated')
            ->whereNotNull('insurance_type_id')
            ->whereNotNull('insurance_subtype_id')
            ->groupBy('insurance_id', 'insurance_type_id', 'insurance_subtype_id')
            ->get()
            ->groupBy('insurance_id');

        $eligibleInsuranceIds = $countsPerInsurance
            ->filter(fn ($cnt) => $cnt >= $min)
            ->keys()
            ->all();

        if (empty($eligibleInsuranceIds)) {
            $this->warn("Keine Versicherung erreicht den Mindestwert von {$min} Bewertungen.");
            return Command::SUCCESS;
        }

        Insurance::query()
            ->whereIn('id', $eligibleInsuranceIds)
            ->with([
                'insuranceTypes:id,name',
                'subtypes' => function ($query) {
                    $query->select('insurance_subtypes.id', 'insurance_subtypes.name');
                },
            ])
            ->orderBy('id')
            ->chunk(100, function ($insurances) use ($min, $countsPerInsurance, $countsPerType, $countsPerSubtype, $countsPerTypeAndSubtype) {
                foreach ($insurances as $insurance) {
                    $insuranceId = $insurance->id;
                    $total = (int) ($countsPerInsurance[$insuranceId] ?? 0);

                    if ($total >= $min) {
                        EvaluateDetailInsuranceRatingWithAI::dispatch($insurance);
                        $this->line("-> Overall AI-Job: {$insurance->name} ({$total} ratings)");
                    }

                    $typeRows = $countsPerType[$insuranceId] ?? collect();
                    foreach ($typeRows as $typeRow) {
                        $typeId = (int) $typeRow->insurance_type_id;
                        $count = (int) $typeRow->cnt;

                        if ($count < $min) {
                            continue;
                        }

                        EvaluateDetailInsuranceRatingWithAI::dispatch($insurance, null, $typeId);
                        $typeName = optional($insurance->insuranceTypes->firstWhere('id', $typeId))->name ?? "Type #{$typeId}";
                        $this->line("   -> Type AI-Job: {$insurance->name} / {$typeName} ({$count} ratings)");
                    }

                    $subtypeRows = $countsPerSubtype[$insuranceId] ?? collect();
                    foreach ($subtypeRows as $subtypeRow) {
                        $subtypeId = (int) $subtypeRow->insurance_subtype_id;
                        $count = (int) $subtypeRow->cnt;

                        if ($count < $min) {
                            continue;
                        }

                        EvaluateDetailInsuranceRatingWithAI::dispatch($insurance, $subtypeId);
                        $subtypeName = optional($insurance->subtypes->firstWhere('id', $subtypeId))->name ?? "Subtype #{$subtypeId}";
                        $this->line("   -> Subtype AI-Job: {$insurance->name} / {$subtypeName} ({$count} ratings)");
                    }

                    $typeSubtypeRows = $countsPerTypeAndSubtype[$insuranceId] ?? collect();
                    foreach ($typeSubtypeRows as $typeSubtypeRow) {
                        $typeId = (int) $typeSubtypeRow->insurance_type_id;
                        $subtypeId = (int) $typeSubtypeRow->insurance_subtype_id;
                        $count = (int) $typeSubtypeRow->cnt;

                        if ($count < $min) {
                            continue;
                        }

                        EvaluateDetailInsuranceRatingWithAI::dispatch($insurance, $subtypeId, $typeId);
                        $typeName = optional($insurance->insuranceTypes->firstWhere('id', $typeId))->name ?? "Type #{$typeId}";
                        $subtypeName = optional($insurance->subtypes->firstWhere('id', $subtypeId))->name ?? "Subtype #{$subtypeId}";
                        $this->line("   -> Type/Subtype AI-Job: {$insurance->name} / {$typeName} / {$subtypeName} ({$count} ratings)");
                    }
                }
            });

        $this->info('Alle relevanten Jobs wurden erfolgreich dispatcht.');
        return Command::SUCCESS;
    }
}
