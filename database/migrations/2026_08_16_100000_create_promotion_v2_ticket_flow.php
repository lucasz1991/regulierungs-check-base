<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MariaDB/MySQL commit many DDL statements implicitly. Validate the
        // existing encrypted settings before the first schema mutation so a
        // bad key/MAC can never leave an unregistered half-applied migration.
        $this->verifiedPromotionSettings(false);

        Schema::table('campaigns', function (Blueprint $table): void {
            $table->string('landing_headline')->nullable()->after('name');
            $table->text('landing_text')->nullable()->after('landing_headline');
            $table->text('rules_text')->nullable()->after('landing_text');
            $table->string('quota_exhaustion_policy', 32)->default('block')->after('ends_at');
            $table->boolean('is_public')->default(false)->index()->after('is_active');
            $table->unsignedTinyInteger('public_slot')->nullable()->unique()->after('is_public');
        });

        Schema::table('prizes', function (Blueprint $table): void {
            $table->string('outcome_type', 32)->default('prize')->after('name');
            $table->unsignedInteger('awarded_count')->default(0)->after('reserved_count');
        });

        // Existing, non-cancelled V1 reservations continue to consume quota.
        DB::table('prizes')->update(['awarded_count' => DB::raw('reserved_count')]);

        Schema::create('promotion_tickets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('participation_id')->unique()->constrained('participations')->restrictOnDelete();
            $table->foreignId('campaign_id')->constrained('campaigns')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->index();
            $table->dateTime('issued_at');
            $table->dateTime('activated_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'user_id']);
            $table->index(['campaign_id', 'status', 'issued_at']);
        });

        Schema::create('promotion_turns', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ticket_id')->constrained('promotion_tickets')->restrictOnDelete();
            $table->foreignId('campaign_id')->constrained('campaigns')->restrictOnDelete();
            $table->foreignId('started_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('released_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->index();
            $table->dateTime('started_at');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('released_at')->nullable();
            $table->string('release_reason', 120)->nullable();
            $table->timestamps();

            $table->index(['campaign_id', 'status', 'started_at']);
            $table->index(['ticket_id', 'started_at']);
        });

        Schema::create('promotion_campaign_states', function (Blueprint $table): void {
            $table->foreignId('campaign_id')->primary()->constrained('campaigns')->restrictOnDelete();
            $table->foreignId('active_turn_id')->nullable()->unique()->constrained('promotion_turns')->nullOnDelete();
            $table->boolean('sticker_required')->default(false);
            $table->dateTime('sticker_acknowledged_at')->nullable();
            $table->foreignId('sticker_acknowledged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('promotion_spin_results', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('turn_id')->constrained('promotion_turns')->restrictOnDelete();
            $table->foreignId('ticket_id')->constrained('promotion_tickets')->restrictOnDelete();
            $table->foreignId('campaign_id')->constrained('campaigns')->restrictOnDelete();
            $table->foreignId('prize_id')->nullable()->constrained('prizes')->restrictOnDelete();
            $table->unsignedSmallInteger('sequence');
            $table->string('outcome_type_snapshot', 32);
            $table->string('label_snapshot');
            $table->string('fulfillment_mode_snapshot', 32)->nullable();
            $table->boolean('is_final')->default(false);
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('recorded_at');
            $table->foreignId('corrects_result_id')->nullable()->constrained('promotion_spin_results')->nullOnDelete();
            $table->dateTime('superseded_at')->nullable();
            $table->string('correction_reason', 255)->nullable();
            $table->string('mail_status', 20)->default('not_required')->index();
            $table->dateTime('mail_sent_at')->nullable();
            $table->dateTime('mail_failed_at')->nullable();
            $table->dateTime('mail_last_attempted_at')->nullable();
            $table->char('mail_error_digest', 64)->nullable();
            $table->foreignId('fulfilled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('fulfilled_at')->nullable();
            $table->timestamps();

            $table->unique(['turn_id', 'sequence']);
            $table->index(['ticket_id', 'is_final', 'superseded_at']);
            $table->index(['campaign_id', 'recorded_at']);
        });

        Schema::table('promotion_settings', function (Blueprint $table): void {
            $table->foreignId('public_campaign_id')->nullable()->after('id')->constrained('campaigns')->nullOnDelete();
        });
        $this->resignPromotionSettings(true);

        Schema::create('social_auth_provider_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('provider', 32)->unique();
            $table->boolean('enabled')->default(false);
            $table->string('client_id', 255)->nullable();
            $table->text('client_secret_encrypted')->nullable();
            $table->string('redirect_uri', 2048)->nullable();
            $table->string('apple_team_id', 64)->nullable();
            $table->string('apple_key_id', 64)->nullable();
            $table->dateTime('client_secret_expires_at')->nullable();
            $table->char('configuration_mac', 64)->nullable();
            $table->timestamps();
        });

        Schema::create('social_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('provider', 32);
            $table->string('provider_user_id', 255);
            $table->string('provider_email')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_user_id']);
            $table->unique(['user_id', 'provider']);
        });

        Schema::table('win_events', function (Blueprint $table): void {
            $table->foreignId('ticket_id')->nullable()->after('win_id')->constrained('promotion_tickets')->nullOnDelete();
            $table->foreignId('turn_id')->nullable()->after('ticket_id')->constrained('promotion_turns')->nullOnDelete();
            $table->foreignId('spin_result_id')->nullable()->after('turn_id')->constrained('promotion_spin_results')->nullOnDelete();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE campaigns ADD CONSTRAINT promotion_campaigns_quota_policy_check CHECK (quota_exhaustion_policy IN ('block', 'sticker_continue'))");
            DB::statement('ALTER TABLE campaigns ADD CONSTRAINT promotion_campaigns_public_slot_check CHECK ((is_public = 0 AND public_slot IS NULL) OR (is_public = 1 AND public_slot = 1))');
            DB::statement("ALTER TABLE prizes ADD CONSTRAINT promotion_prizes_outcome_type_check CHECK (outcome_type IN ('prize', 'no_win', 'retry'))");
            DB::statement('ALTER TABLE prizes ADD CONSTRAINT promotion_prizes_awarded_quota_check CHECK (awarded_count <= quota)');
            DB::statement("ALTER TABLE promotion_tickets ADD CONSTRAINT promotion_tickets_status_check CHECK (status IN ('ready', 'active', 'completed', 'cancelled'))");
            DB::statement("ALTER TABLE promotion_turns ADD CONSTRAINT promotion_turns_status_check CHECK (status IN ('active', 'completed', 'released'))");
            DB::statement("ALTER TABLE promotion_spin_results ADD CONSTRAINT promotion_spin_results_outcome_check CHECK (outcome_type_snapshot IN ('prize', 'no_win', 'retry', 'quota_reroll'))");
            DB::statement("ALTER TABLE promotion_spin_results ADD CONSTRAINT promotion_spin_results_mail_check CHECK (mail_status IN ('pending', 'sent', 'failed', 'not_required'))");
        }
    }

    public function down(): void
    {
        $this->verifiedPromotionSettings(true);

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE campaigns DROP CONSTRAINT promotion_campaigns_quota_policy_check');
            DB::statement('ALTER TABLE campaigns DROP CONSTRAINT promotion_campaigns_public_slot_check');
            DB::statement('ALTER TABLE prizes DROP CONSTRAINT promotion_prizes_outcome_type_check');
            DB::statement('ALTER TABLE prizes DROP CONSTRAINT promotion_prizes_awarded_quota_check');
            DB::statement('ALTER TABLE promotion_tickets DROP CONSTRAINT promotion_tickets_status_check');
            DB::statement('ALTER TABLE promotion_turns DROP CONSTRAINT promotion_turns_status_check');
            DB::statement('ALTER TABLE promotion_spin_results DROP CONSTRAINT promotion_spin_results_outcome_check');
            DB::statement('ALTER TABLE promotion_spin_results DROP CONSTRAINT promotion_spin_results_mail_check');
        }

        Schema::table('win_events', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('spin_result_id');
            $table->dropConstrainedForeignId('turn_id');
            $table->dropConstrainedForeignId('ticket_id');
        });

        Schema::dropIfExists('social_accounts');
        Schema::dropIfExists('social_auth_provider_settings');

        $this->resignPromotionSettings(false);
        Schema::table('promotion_settings', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('public_campaign_id');
        });

        Schema::dropIfExists('promotion_spin_results');
        Schema::dropIfExists('promotion_campaign_states');
        Schema::dropIfExists('promotion_turns');
        Schema::dropIfExists('promotion_tickets');

        Schema::table('prizes', function (Blueprint $table): void {
            $table->dropColumn(['outcome_type', 'awarded_count']);
        });

        Schema::table('campaigns', function (Blueprint $table): void {
            $table->dropUnique(['public_slot']);
            $table->dropColumn([
                'landing_headline',
                'landing_text',
                'rules_text',
                'quota_exhaustion_policy',
                'is_public',
                'public_slot',
            ]);
        });
    }

    private function resignPromotionSettings(bool $targetIncludesPublicCampaign): void
    {
        $verified = $this->verifiedPromotionSettings(! $targetIncludesPublicCampaign);
        if ($verified === null) {
            return;
        }

        [$setting, $secret] = $verified;
        $target = $this->settingsMacMaterial($setting, $targetIncludesPublicCampaign);

        DB::table('promotion_settings')->where('id', 1)->update([
            'configuration_mac' => hash_hmac('sha256', json_encode($target, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $secret),
            'updated_at' => now(),
        ]);
    }

    /** @return array{object, string}|null */
    private function verifiedPromotionSettings(bool $includePublicCampaign): ?array
    {
        $setting = DB::table('promotion_settings')->where('id', 1)->first();
        if (! $setting) {
            return null;
        }

        try {
            $secret = base64_decode(Crypt::decryptString((string) $setting->audit_secret_encrypted), true);
        } catch (Throwable $exception) {
            throw new RuntimeException('Der Promotion-Schluessel konnte fuer die V2-Migration nicht entschluesselt werden.', 0, $exception);
        }
        if ($secret === false || strlen($secret) !== 32) {
            throw new RuntimeException('Der Promotion-Schluessel ist fuer die V2-Migration ungueltig.');
        }

        $source = $this->settingsMacMaterial($setting, $includePublicCampaign);
        $stored = (string) $setting->configuration_mac;
        $expected = hash_hmac('sha256', json_encode($source, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $secret);
        if (preg_match('/\A[a-f0-9]{64}\z/', $stored) !== 1 || ! hash_equals($stored, $expected)) {
            throw new RuntimeException('Die Promotion-Einstellungen sind manipuliert; die V2-Migration wurde sicher abgebrochen.');
        }

        return [$setting, $secret];
    }

    /** @return array<string, bool|int|string|null> */
    private function settingsMacMaterial(object $setting, bool $includePublicCampaign): array
    {
        if ($includePublicCampaign) {
            return [
                'enabled' => (bool) $setting->enabled,
                'public_campaign_id' => $setting->public_campaign_id === null ? null : (int) $setting->public_campaign_id,
                'qr_ttl_minutes' => (int) $setting->qr_ttl_minutes,
                'redemption_base_url' => rtrim(trim((string) $setting->redemption_base_url), '/'),
            ];
        }

        return [
            'enabled' => (bool) $setting->enabled,
            'qr_ttl_minutes' => (int) $setting->qr_ttl_minutes,
            'redemption_base_url' => rtrim(trim((string) $setting->redemption_base_url), '/'),
        ];
    }
};
