<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('source_key', 80)->unique();
            $table->string('source_type', 10);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('email', 254)->nullable()->index();
            $table->uuid('address_token');
            $table->boolean('enabled')->default(true);
            $table->boolean('neutral_greeting')->default(false);
            $table->timestamp('consented_at')->nullable();
            $table->string('consent_source', 40)->nullable();
            $table->text('consent_note')->nullable();
            $table->unsignedBigInteger('version')->default(1);
            $table->timestamps();
        });
        Schema::create('newsletter_suppressions', function (Blueprint $table) {
            $table->string('email', 254)->primary();
            $table->timestamp('created_at');
        });
        Schema::create('newsletter_preference_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('newsletter_subscriptions');
            $table->string('email', 254)->nullable();
            $table->string('event', 40);
            $table->string('source', 40);
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_preference_events');
        Schema::dropIfExists('newsletter_suppressions');
        Schema::dropIfExists('newsletter_subscriptions');
    }
};
