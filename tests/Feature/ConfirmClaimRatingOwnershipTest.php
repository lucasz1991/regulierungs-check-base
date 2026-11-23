<?php

namespace Tests\Feature;

use App\Livewire\Profile\ClaimRating\ConfirmClaimRating;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class ConfirmClaimRatingOwnershipTest extends TestCase
{
    private User $owner;

    private User $intruder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->timestamps();
        });
        Schema::create('claim_ratings', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('status');
            $table->boolean('is_public')->default(false);
            $table->json('answers')->nullable();
            $table->json('attachments')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $this->owner = $this->user(1, 'owner@example.test', verified: true);
        $this->intruder = $this->user(2, 'intruder@example.test', verified: true);

        // Status "rating" keeps the dialog on its loading branch, so the view needs no tags or scorings.
        DB::table('claim_ratings')->insert([
            'id' => 10,
            'user_id' => $this->owner->id,
            'status' => 'rating',
            'is_public' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_foreign_rating_id_does_not_open_the_dialog(): void
    {
        $this->actingAs($this->intruder);

        Livewire::test(ConfirmClaimRating::class)
            ->call('claimRatingConfirm', 10)
            ->assertSet('openModal', false)
            ->assertSet('claimRating', null);
    }

    public function test_foreign_rating_cannot_be_published(): void
    {
        $this->actingAs($this->intruder);

        Livewire::test(ConfirmClaimRating::class)
            ->call('claimRatingConfirm', 10)
            ->call('confirm');

        $this->assertFalse((bool) DB::table('claim_ratings')->where('id', 10)->value('is_public'));
    }

    public function test_owner_can_publish_own_rating(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(ConfirmClaimRating::class)
            ->call('claimRatingConfirm', 10)
            ->assertSet('openModal', true)
            ->call('confirm')
            ->assertSet('openModal', false)
            ->assertDispatched('refreshParent');

        $this->assertTrue((bool) DB::table('claim_ratings')->where('id', 10)->value('is_public'));
    }

    public function test_owner_without_verified_email_cannot_publish(): void
    {
        $unverified = $this->user(3, 'unverified@example.test', verified: false);
        DB::table('claim_ratings')->where('id', 10)->update(['user_id' => $unverified->id]);
        $this->actingAs($unverified);

        Livewire::test(ConfirmClaimRating::class)
            ->call('claimRatingConfirm', 10)
            ->call('confirm');

        $this->assertFalse((bool) DB::table('claim_ratings')->where('id', 10)->value('is_public'));
    }

    public function test_second_rating_needing_verification_cannot_be_published_from_the_dialog(): void
    {
        DB::table('claim_ratings')->insert([
            'id' => 11,
            'user_id' => $this->owner->id,
            'status' => 'published',
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->actingAs($this->owner);

        Livewire::test(ConfirmClaimRating::class)
            ->call('claimRatingConfirm', 10)
            ->call('confirm');

        $this->assertFalse((bool) DB::table('claim_ratings')->where('id', 10)->value('is_public'));
    }

    public function test_foreign_rating_cannot_be_reanalyzed(): void
    {
        $this->actingAs($this->intruder);

        Livewire::test(ConfirmClaimRating::class)
            ->call('claimRatingConfirm', 10)
            ->call('reanalyze')
            ->assertForbidden();
    }

    private function user(int $id, string $email, bool $verified): User
    {
        DB::table('users')->insert([
            'id' => $id,
            'name' => 'Test '.$id,
            'email' => $email,
            'password' => 'not-used',
            'email_verified_at' => $verified ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }
}
