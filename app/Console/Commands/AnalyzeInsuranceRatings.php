<?php

namespace App\Console\Commands;

use App\Models\Insurance;
use App\Models\InsuranceSubType;
use App\Jobs\EvaluateDetailInsuranceRatingWithAI;
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
    protected $description = 'Analysiert alle Versicherungen und Subtypen, bei denen mindestens zwei Bewertungen vorliegen, und startet die KI-basierte Detailauswertung.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $insurances = Insurance::with('subtypes')->get();

        foreach ($insurances as $insurance) {
            // Allgemeine Bewertungen zählen
            $generalRatingCount = $insurance->claimRatings()
                ->where('status', 'rated')
                ->count();

            if ($generalRatingCount >= 5) {
                EvaluateDetailInsuranceRatingWithAI::dispatch($insurance, null);
                $this->info("Dispatched general AI job for insurance: {$insurance->name}");
            }

            foreach ($insurance->subtypes as $subtype) {
                $subtypeRatingCount = $insurance->claimRatings()
                    ->where('insurance_subtype_id', $subtype->id)
                    ->where('status', 'rated')
                    ->count();

                if ($subtypeRatingCount >= 5) {
                    EvaluateDetailInsuranceRatingWithAI::dispatch($insurance, $subtype->id);
                    $this->info("Dispatched subtype AI job for insurance: {$insurance->name} / {$subtype->name}");
                }
            }
        }

        $this->info('Alle Jobs wurden erfolgreich dispatcht.');
    }
}
