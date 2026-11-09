<?php

namespace Tests\Feature;

use App\Livewire\Pages\Ranking;
use App\Models\ClaimRating;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class RankingFiltersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());

        Schema::create('insurances', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('initials')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
        });
        Schema::create('insurance_subtypes', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug');
        });
        Schema::create('claim_ratings', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('insurance_id');
            $table->unsignedInteger('insurance_type_id')->nullable();
            $table->unsignedInteger('insurance_subtype_id')->nullable();
            $table->string('status');
            $table->boolean('is_public');
            $table->decimal('rating_score', 6, 3)->nullable();
            $table->json('answers')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        foreach ([1 => 'Alpha', 2 => 'Beta', 3 => 'Zero', 4 => 'Private', 5 => 'Pending', 6 => 'Deleted', 7 => 'Missing', 8 => 'Malformed', 9 => 'Tie'] as $id => $name) {
            DB::table('insurances')->insert(['id' => $id, 'name' => $name, 'slug' => strtolower($name), 'initials' => $name]);
        }
        foreach ([1 => 'Auto', 2 => 'Hausrat', 3 => 'Private Sparte', 4 => 'Ohne Score'] as $id => $name) {
            DB::table('insurance_subtypes')->insert(['id' => $id, 'name' => $name, 'slug' => 'subtype-'.$id]);
        }

        $this->rating(1, 1, 1, 0.6, [0.2, 0.9, 0.8, 0.4]);
        $this->rating(2, 1, 2, 0.8, [0.8, 0.1, 0.2, 0.6], ['status' => ClaimRating::STATUS_PUBLISHED]);
        $this->rating(3, 2, 1, 0.8, [0.9, 0.2, 0.6, 0.9]);
        $this->rating(4, 3, 1, 0, [0, 0, 1, null]);
        $this->rating(5, 4, 3, 1, [1, 1, 1, 1], ['is_public' => false]);
        $this->rating(6, 5, 3, 1, [1, 1, 1, 1], ['status' => ClaimRating::STATUS_PENDING]);
        $this->rating(7, 6, 3, 1, [1, 1, 1, 1], ['deleted_at' => now()]);
        $this->rating(8, 7, 4, null, [null, null, null, null]);
        $this->rating(9, 8, 1, 0.5, ['broken', 2, false, -1]);
        $this->rating(10, 9, 1, 0.8, [0.9, 0.2, 0.6, 0.9]);
    }

    public function test_general_ranking_uses_public_scores_and_excludes_private_pending_deleted_and_missing_data(): void
    {
        Livewire::test(Ranking::class)
            ->assertSet('selectedAspect', 'allgemein')
            ->assertSet('insuranceSubTypes', fn ($types) => $types->modelKeys() === [1, 2, 4])
            ->assertViewHas('allInsurances', fn ($items) => $items->modelKeys() === [2, 9, 1, 8, 3])
            ->assertViewHas('allInsurances', fn ($items) => abs($items->firstWhere('id', 1)->ranking_score - 0.7) < 0.00001)
            ->assertViewHas('allInsurances', fn ($items) => $items->last()->ranking_score === 0.0)
            ->assertDontSee('Private Sparte')
            ->assertDontSee('Pending')
            ->assertDontSee('Deleted')
            ->assertDontSee('Missing');
    }

    public function test_each_aspect_uses_its_stored_score_and_preserves_real_zero_without_inventing_missing_scores(): void
    {
        $component = Livewire::test(Ranking::class);

        foreach ([
            'geschwindigkeit' => [2, 9, 1, 3],
            'kunden_service' => [1, 2, 9, 3],
            'fairness' => [3, 2, 9, 1],
            'transparenz' => [2, 9, 1],
        ] as $aspect => $ids) {
            $component->set('selectedAspect', $aspect)
                ->assertViewHas('allInsurances', fn ($items) => $items->modelKeys() === $ids)
                ->assertDontSee('Malformed');
        }

        $component->set('selectedAspect', 'geschwindigkeit')
            ->assertViewHas('allInsurances', fn ($items) => $items->last()->ranking_score === 0.0)
            ->assertSee('0,0 / 5');
    }

    public function test_subtype_changes_refresh_ranking_and_card_aggregates_and_reset_restores_both_filters(): void
    {
        Livewire::test(Ranking::class)
            ->set('selectedAspect', 'kunden_service')
            ->set('selectedInsuranceSubTypefilter', [2])
            ->assertViewHas('allInsurances', fn ($items) => $items->modelKeys() === [1])
            ->assertViewHas('allInsurances', fn ($items) => abs($items->first()->ranking_score - 0.1) < 0.00001)
            ->assertViewHas('selectedSubtypeIds', [2])
            ->assertSeeHtml('aria-label="4,0 von 5 Sternen"')
            ->call('resetFilters')
            ->assertSet('selectedAspect', 'allgemein')
            ->assertSet('selectedInsuranceSubTypefilter', [])
            ->assertViewHas('selectedSubtypeIds', [])
            ->assertViewHas('allInsurances', fn ($items) => $items->modelKeys() === [2, 9, 1, 8, 3]);
    }

    public function test_filters_handle_empty_results_invalid_values_and_livewire_refresh_with_stable_ties(): void
    {
        $component = Livewire::test(Ranking::class)
            ->set('selectedInsuranceSubTypefilter', [4])
            ->assertViewHas('allInsurances', fn ($items) => $items->isEmpty())
            ->assertSee('Für diese Auswahl liegen noch keine auswertbaren Bewertungen vor.')
            ->set('selectedInsuranceSubTypefilter', [-1, 'bad', 2, 2])
            ->assertSet('selectedInsuranceSubTypefilter', [2])
            ->assertViewHas('allInsurances', fn ($items) => $items->modelKeys() === [1])
            ->set('selectedAspect', 'untrusted->column')
            ->assertSet('selectedAspect', 'allgemein')
            ->call('resetFilters');

        $this->rating(11, 9, 1, 0.8, [0.9, 0.2, 0.6, 0.9]);
        $component->call('$refresh')
            ->assertViewHas('allInsurances', fn ($items) => $items->modelKeys() === [9, 2, 1, 8, 3]);
    }

    public function test_progressive_results_preserve_full_ranking_order_and_reset_on_filter_changes(): void
    {
        // The five existing ranked insurers plus sixteen additions make 21.
        for ($id = 100; $id < 116; $id++) {
            DB::table('insurances')->insert(['id' => $id, 'name' => 'Extra '.$id, 'slug' => 'extra-'.$id]);
            $this->rating($id, $id, 2, 0.1, [0.1, 0.1, 0.1, 0.1]);
        }

        Livewire::test(Ranking::class)
            ->assertSet('visibleCount', 20)
            ->assertViewHas('allInsurances', fn ($items) => $items->count() === 20 && ! $items->contains('id', 3))
            ->assertViewHas('totalInsurances', 21)
            ->assertViewHas('hasMoreInsurances', true)
            ->assertSee('21 Versicherer')
            ->assertSee('Weitere Versicherer laden')
            ->call('loadMore')
            ->assertSet('visibleCount', 40)
            ->assertViewHas('allInsurances', fn ($items) => $items->count() === 21 && $items->last()->id === 3)
            ->assertViewHas('totalInsurances', 21)
            ->assertViewHas('hasMoreInsurances', false)
            ->assertDontSee('Weitere Versicherer laden')
            ->set('selectedInsuranceSubTypefilter', [2])
            ->assertSet('visibleCount', 20)
            ->assertViewHas('allInsurances', fn ($items) => $items->count() === 17)
            ->assertViewHas('totalInsurances', 17)
            ->call('loadMore')
            ->set('selectedAspect', 'geschwindigkeit')
            ->assertSet('visibleCount', 20)
            ->call('loadMore')
            ->call('resetFilters')
            ->assertSet('visibleCount', 20)
            ->assertSet('selectedInsuranceSubTypefilter', [])
            ->assertSet('selectedAspect', 'allgemein')
            ->assertViewHas('totalInsurances', 21)
            ->assertViewHas('allInsurances', fn ($items) => $items->count() === 20 && ! $items->contains('id', 3));
    }

    private function rating(int $id, int $insuranceId, int $subtypeId, ?float $overall, array $aspects, array $overrides = []): void
    {
        DB::table('claim_ratings')->insert(array_merge([
            'id' => $id,
            'insurance_id' => $insuranceId,
            'insurance_subtype_id' => $subtypeId,
            'status' => ClaimRating::STATUS_RATED,
            'is_public' => true,
            'rating_score' => $overall,
            'attachments' => json_encode(['scorings' => array_combine(['regulation_speed', 'customer_service', 'fairness', 'transparency'], $aspects)]),
            'answers' => json_encode(['is_closed' => true, 'selectedDates' => ['started_at' => '2026-10-01', 'ended_at' => '2026-10-11']]),
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }
}
