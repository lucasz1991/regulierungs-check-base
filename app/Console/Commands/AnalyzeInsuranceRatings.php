<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Insurance;
use App\Models\ClaimRating;
use App\Jobs\EvaluateDetailInsuranceRatingWithAI;

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
    protected $description = 'Analysiert alle Versicherungen und Subtypen mit mindestens 100 ClaimRatings (status=rated) und startet die KI-Detailauswertung.';

    /**
     * Mindestanzahl an Bewertungen.
     *
     * @var int
     */
    protected int $MIN_COUNT = 100;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $min = $this->MIN_COUNT;

        $this->info("Starte KI-Analyse aller Versicherungen (Mindestanzahl: {$min} Bewertungen)...");

        // === 1. Aggregierte Zählung pro Versicherung ===
        $countsPerInsurance = ClaimRating::query()
            ->selectRaw('insurance_id, COUNT(*) as cnt')
            ->where('status', 'rated')
            ->groupBy('insurance_id')
            ->pluck('cnt', 'insurance_id'); // [insurance_id => count]

        if ($countsPerInsurance->isEmpty()) {
            $this->warn('Keine ClaimRatings mit Status "rated" gefunden.');
            return Command::SUCCESS;
        }

        // === 2. Aggregierte Zählung pro (Versicherung, Subtyp) ===
        $countsPerSubtype = ClaimRating::query()
            ->selectRaw('insurance_id, insurance_subtype_id, COUNT(*) as cnt')
            ->where('status', 'rated')
            ->whereNotNull('insurance_subtype_id')
            ->groupBy('insurance_id', 'insurance_subtype_id')
            ->get()
            ->groupBy('insurance_id');

        // === 3. Relevante Versicherungen (mit >=100 Ratings) ===
        $eligibleInsuranceIds = $countsPerInsurance
            ->filter(fn ($cnt) => $cnt >= $min)
            ->keys()
            ->all();

        if (empty($eligibleInsuranceIds)) {
            $this->warn("Keine Versicherung erreicht den Mindestwert von {$min} Bewertungen.");
            return Command::SUCCESS;
        }

        // === 4. Chunkweise Verarbeitung, um Speicher zu schonen ===
        Insurance::query()
            ->whereIn('id', $eligibleInsuranceIds)
            ->with(['subtypes:id,insurance_id,name'])
            ->orderBy('id')
            ->chunk(100, function ($insurances) use ($min, $countsPerInsurance, $countsPerSubtype) {
                foreach ($insurances as $insurance) {
                    $iid = $insurance->id;
                    $total = (int) ($countsPerInsurance[$iid] ?? 0);

                    // --- Allgemeine Analyse (Versicherung) ---
                    if ($total >= $min) {
                        EvaluateDetailInsuranceRatingWithAI::dispatch($insurance, null);
                        $this->line("→ General AI-Job: {$insurance->name} ({$total} ratings)");
                    }

                    // --- Subtypenanalyse ---
                    $subtypeRows = ($countsPerSubtype[$iid] ?? collect());
                    if ($subtypeRows->isEmpty()) {
                        continue;
                    }

                    $subtypeCounts = $subtypeRows->pluck('cnt', 'insurance_subtype_id');
                    foreach ($insurance->subtypes as $subtype) {
                        $sid = $subtype->id;
                        $cnt = (int) ($subtypeCounts[$sid] ?? 0);

                        if ($cnt >= $min) {
                            EvaluateDetailInsuranceRatingWithAI::dispatch($insurance, $sid);
                            $this->line("   ↳ Subtype AI-Job: {$insurance->name} / {$subtype->name} ({$cnt} ratings)");
                        }
                    }
                }
            });

        $this->info('Alle relevanten Jobs wurden erfolgreich dispatcht.');
        return Command::SUCCESS;
    }
}
