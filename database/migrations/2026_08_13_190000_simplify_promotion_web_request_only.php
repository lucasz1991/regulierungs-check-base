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
        $this->migrateSettingsMac();

        if (Schema::hasTable('promotion_settings')) {
            Schema::table('promotion_settings', function (Blueprint $table): void {
                if (Schema::hasColumn('promotion_settings', 'audit_email')) {
                    $table->dropColumn('audit_email');
                }

                if (Schema::hasColumn('promotion_settings', 'access_context_retention_months')) {
                    $table->dropColumn('access_context_retention_months');
                }
            });
        }

        Schema::dropIfExists('promotion_access_contexts');

        if (Schema::hasTable('promotion_audit_heads')) {
            Schema::table('promotion_audit_heads', function (Blueprint $table): void {
                if (Schema::hasColumn('promotion_audit_heads', 'last_anchored_sequence')) {
                    $table->dropColumn('last_anchored_sequence');
                }

                if (Schema::hasColumn('promotion_audit_heads', 'last_anchored_at')) {
                    $table->dropColumn('last_anchored_at');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('promotion_settings')) {
            Schema::table('promotion_settings', function (Blueprint $table): void {
                if (! Schema::hasColumn('promotion_settings', 'audit_email')) {
                    $table->string('audit_email')->nullable();
                }

                if (! Schema::hasColumn('promotion_settings', 'access_context_retention_months')) {
                    $table->unsignedSmallInteger('access_context_retention_months')->default(24);
                }
            });

            $this->restoreLegacySettingsMac();
        }

        if (Schema::hasTable('promotion_audit_heads')) {
            Schema::table('promotion_audit_heads', function (Blueprint $table): void {
                if (! Schema::hasColumn('promotion_audit_heads', 'last_anchored_sequence')) {
                    $table->unsignedBigInteger('last_anchored_sequence')->default(0);
                }

                if (! Schema::hasColumn('promotion_audit_heads', 'last_anchored_at')) {
                    $table->dateTime('last_anchored_at')->nullable();
                }
            });
        }

        if (! Schema::hasTable('promotion_access_contexts') && Schema::hasTable('win_events')) {
            Schema::create('promotion_access_contexts', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('win_event_id')->unique()->constrained('win_events')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->dateTime('delete_after')->index();
                $table->dateTime('created_at');
            });
        }
    }

    private function migrateSettingsMac(): void
    {
        if (! Schema::hasTable('promotion_settings')
            || ! Schema::hasColumn('promotion_settings', 'audit_email')
            || ! Schema::hasColumn('promotion_settings', 'access_context_retention_months')) {
            return;
        }

        $setting = DB::table('promotion_settings')->where('id', 1)->first();
        if (! $setting) {
            return;
        }

        $secret = $this->decryptSecret((string) $setting->audit_secret_encrypted);
        $legacyMac = $this->mac([
            'access_context_retention_months' => (int) $setting->access_context_retention_months,
            'audit_email' => trim((string) $setting->audit_email),
            'enabled' => (bool) $setting->enabled,
            'qr_ttl_minutes' => (int) $setting->qr_ttl_minutes,
            'redemption_base_url' => rtrim(trim((string) $setting->redemption_base_url), '/'),
        ], $secret);

        if (preg_match('/\A[a-f0-9]{64}\z/', (string) $setting->configuration_mac) !== 1
            || ! hash_equals((string) $setting->configuration_mac, $legacyMac)) {
            throw new RuntimeException('Die vorhandenen Promotion-Einstellungen sind manipuliert; die Vereinfachungsmigration wurde sicher abgebrochen.');
        }

        DB::table('promotion_settings')->where('id', 1)->update([
            'configuration_mac' => $this->mac([
                'enabled' => (bool) $setting->enabled,
                'qr_ttl_minutes' => (int) $setting->qr_ttl_minutes,
                'redemption_base_url' => rtrim(trim((string) $setting->redemption_base_url), '/'),
            ], $secret),
            'updated_at' => now(),
        ]);
    }

    private function restoreLegacySettingsMac(): void
    {
        $setting = DB::table('promotion_settings')->where('id', 1)->first();
        if (! $setting) {
            return;
        }

        $secret = $this->decryptSecret((string) $setting->audit_secret_encrypted);
        DB::table('promotion_settings')->where('id', 1)->update([
            'configuration_mac' => $this->mac([
                'access_context_retention_months' => (int) $setting->access_context_retention_months,
                'audit_email' => trim((string) $setting->audit_email),
                'enabled' => (bool) $setting->enabled,
                'qr_ttl_minutes' => (int) $setting->qr_ttl_minutes,
                'redemption_base_url' => rtrim(trim((string) $setting->redemption_base_url), '/'),
            ], $secret),
            'updated_at' => now(),
        ]);
    }

    /** @param array<string, bool|int|string> $material */
    private function mac(array $material, string $secret): string
    {
        return hash_hmac(
            'sha256',
            json_encode($material, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            $secret,
        );
    }

    private function decryptSecret(string $encrypted): string
    {
        try {
            $secret = base64_decode(Crypt::decryptString($encrypted), true);
        } catch (Throwable $exception) {
            throw new RuntimeException('Der vorhandene Promotion-Auditschluessel konnte fuer die Vereinfachungsmigration nicht entschluesselt werden.', 0, $exception);
        }

        if ($secret === false || strlen($secret) !== 32) {
            throw new RuntimeException('Der vorhandene Promotion-Auditschluessel ist ungueltig.');
        }

        return $secret;
    }
};
