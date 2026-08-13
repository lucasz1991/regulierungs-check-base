<?php

namespace Tests\Feature\Promotion;

use App\Models\PromotionSetting;
use App\Services\Promotion\PromotionSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Tests\TestCase;

class PromotionSettingsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_bootstraps_an_encrypted_disabled_singleton(): void
    {
        $setting = PromotionSetting::query()->findOrFail(1);
        $snapshot = app(PromotionSettingsService::class)->get();

        $this->assertFalse($snapshot['enabled']);
        $this->assertFalse($snapshot['requested_enabled']);
        $this->assertFalse($snapshot['is_configured']);
        $this->assertTrue($snapshot['audit_key_configured']);
        $this->assertFalse(Schema::hasColumn('promotion_settings', 'audit_email'));
        $this->assertFalse(Schema::hasColumn('promotion_settings', 'access_context_retention_months'));
        $this->assertArrayNotHasKey('audit_secret_encrypted', $snapshot);
        $this->assertArrayNotHasKey('configuration_mac', $snapshot);
        $this->assertSame(32, strlen(base64_decode(Crypt::decryptString(
            (string) $setting->getRawOriginal('audit_secret_encrypted'),
        ), true)));
    }

    public function test_valid_admin_save_enables_settings_and_preserves_the_secret(): void
    {
        $service = app(PromotionSettingsService::class);
        $encryptedBefore = PromotionSetting::query()->findOrFail(1)->getRawOriginal('audit_secret_encrypted');

        $saved = $service->save($this->validSettings());

        $this->assertTrue($saved['enabled']);
        $this->assertTrue($saved['is_configured']);
        $this->assertSame('https://promotion.example.test', $saved['redemption_base_url']);
        $this->assertSame($encryptedBefore, PromotionSetting::query()->findOrFail(1)->getRawOriginal('audit_secret_encrypted'));
        $this->assertSame(32, strlen($service->auditKey()));
        $this->assertSame(30, $service->qrTtlMinutes());
        $this->assertFalse(method_exists($service, 'auditEmail'));
        $this->assertFalse(method_exists($service, 'accessContextRetentionMonths'));
    }

    public function test_direct_database_tampering_disables_every_consumer_and_cannot_be_resigned_by_save(): void
    {
        $service = app(PromotionSettingsService::class);
        $service->save($this->validSettings());
        $mac = PromotionSetting::query()->findOrFail(1)->getRawOriginal('configuration_mac');

        DB::table('promotion_settings')->where('id', 1)->update([
            'enabled' => false,
            'redemption_base_url' => 'https://attacker.example.test',
            'qr_ttl_minutes' => 120,
        ]);

        $snapshot = $service->get();
        $this->assertFalse($snapshot['enabled']);
        $this->assertFalse($snapshot['is_configured']);
        $this->assertStringContainsString('ausserhalb', (string) $snapshot['configuration_error']);

        foreach (['auditKey', 'redemptionBaseUrl', 'qrTtlMinutes'] as $method) {
            try {
                $service->{$method}();
                $this->fail("Der manipulierte Consumer {$method} wurde akzeptiert.");
            } catch (RuntimeException $exception) {
                $this->assertStringContainsString('ausserhalb', $exception->getMessage());
            }
        }

        try {
            $service->save($this->validSettings());
            $this->fail('Ein Admin-Save hat manipulierte DB-Werte neu legitimiert.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('ausserhalb', $exception->getMessage());
        }

        $this->assertSame($mac, PromotionSetting::query()->findOrFail(1)->getRawOriginal('configuration_mac'));
    }

    public function test_corrupt_secret_and_missing_migration_fail_closed(): void
    {
        $service = app(PromotionSettingsService::class);
        $service->save($this->validSettings());
        DB::table('promotion_settings')->where('id', 1)->update(['audit_secret_encrypted' => 'corrupt']);

        $this->assertFalse($service->isEnabled());

        $this->expectException(RuntimeException::class);
        $service->auditKey();
    }

    public function test_read_before_migration_is_disabled_without_querying_application_data(): void
    {
        Schema::drop('promotion_settings');

        $snapshot = app(PromotionSettingsService::class)->get();

        $this->assertFalse($snapshot['enabled']);
        $this->assertFalse($snapshot['is_configured']);
        $this->assertStringContainsString('migration', mb_strtolower((string) $snapshot['configuration_error']));
    }

    public function test_upgrade_migration_converts_a_valid_legacy_mac_before_dropping_maintenance_fields(): void
    {
        $service = app(PromotionSettingsService::class);
        $service->save($this->validSettings());

        Schema::table('promotion_settings', function ($table): void {
            $table->string('audit_email')->nullable();
            $table->unsignedSmallInteger('access_context_retention_months')->default(24);
        });

        $setting = PromotionSetting::query()->findOrFail(1);
        $legacyMaterial = [
            'access_context_retention_months' => 24,
            'audit_email' => 'audit@example.test',
            'enabled' => true,
            'qr_ttl_minutes' => 30,
            'redemption_base_url' => 'https://promotion.example.test',
        ];
        DB::table('promotion_settings')->where('id', 1)->update([
            'audit_email' => $legacyMaterial['audit_email'],
            'access_context_retention_months' => $legacyMaterial['access_context_retention_months'],
            'configuration_mac' => hash_hmac(
                'sha256',
                json_encode($legacyMaterial, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                $service->auditKey(),
            ),
        ]);

        $migration = require database_path('migrations/2026_08_13_190000_simplify_promotion_web_request_only.php');
        $migration->up();

        $this->assertFalse(Schema::hasColumn('promotion_settings', 'audit_email'));
        $this->assertFalse(Schema::hasColumn('promotion_settings', 'access_context_retention_months'));
        $this->assertTrue($service->get()['enabled']);
        $this->assertTrue($service->get()['is_configured']);
        $this->assertSame($setting->getRawOriginal('audit_secret_encrypted'), PromotionSetting::query()->findOrFail(1)->getRawOriginal('audit_secret_encrypted'));
    }

    public function test_upgrade_migration_refuses_to_legitimize_tampered_legacy_settings(): void
    {
        app(PromotionSettingsService::class)->save($this->validSettings());
        Schema::table('promotion_settings', function ($table): void {
            $table->string('audit_email')->nullable();
            $table->unsignedSmallInteger('access_context_retention_months')->default(24);
        });
        DB::table('promotion_settings')->where('id', 1)->update([
            'audit_email' => 'attacker@example.test',
            'access_context_retention_months' => 120,
            'configuration_mac' => str_repeat('f', 64),
        ]);

        $migration = require database_path('migrations/2026_08_13_190000_simplify_promotion_web_request_only.php');

        try {
            $migration->up();
            $this->fail('Manipulierte Legacy-Einstellungen wurden durch die Vereinfachungsmigration legitimiert.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('manipuliert', $exception->getMessage());
        }

        $this->assertTrue(Schema::hasColumn('promotion_settings', 'audit_email'));
        $this->assertSame(str_repeat('f', 64), DB::table('promotion_settings')->where('id', 1)->value('configuration_mac'));
    }

    /** @return array<string, mixed> */
    private function validSettings(): array
    {
        return [
            'enabled' => true,
            'redemption_base_url' => 'https://promotion.example.test/',
            'qr_ttl_minutes' => 30,
        ];
    }
}
