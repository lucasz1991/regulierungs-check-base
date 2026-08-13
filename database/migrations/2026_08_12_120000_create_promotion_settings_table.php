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
        $auditSecret = random_bytes(32);

        Schema::create('promotion_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->boolean('enabled')->default(false);
            $table->string('redemption_base_url', 2048)->nullable();
            $table->unsignedSmallInteger('qr_ttl_minutes')->default(30);
            $table->text('audit_secret_encrypted');
            $table->char('configuration_mac', 64);
            $table->timestamps();
        });

        DB::table('promotion_settings')->insert([
            'id' => 1,
            'enabled' => false,
            'redemption_base_url' => null,
            'qr_ttl_minutes' => 30,
            'audit_secret_encrypted' => Crypt::encryptString(base64_encode($auditSecret)),
            'configuration_mac' => hash_hmac('sha256', json_encode([
                'enabled' => false,
                'qr_ttl_minutes' => 30,
                'redemption_base_url' => '',
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $auditSecret),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_settings');
    }
};
