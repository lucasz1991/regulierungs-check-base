<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(false)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->restrictOnDelete();
            $table->string('code', 50);
            $table->string('name');
            $table->string('fulfillment_mode', 32);
            $table->unsignedInteger('quota');
            $table->unsignedInteger('reserved_count')->default(0);
            $table->boolean('is_active')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('configuration')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'code']);
        });

        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('public_id', 64)->unique();
            $table->timestamps();

            $table->unique(['campaign_id', 'user_id']);
        });

        Schema::create('wins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->restrictOnDelete();
            $table->foreignId('prize_id')->constrained('prizes')->restrictOnDelete();
            $table->foreignId('participation_id')->nullable()->constrained('participations')->nullOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('fulfilled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->index();
            $table->char('token_hash', 64)->unique();
            $table->char('claim_key', 64)->nullable()->unique();
            $table->string('prize_name_snapshot');
            $table->dateTime('expires_at')->index();
            $table->dateTime('consumed_at')->nullable();
            $table->dateTime('bound_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('disputed_at')->nullable();
            $table->dateTime('fulfilled_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->index(['campaign_id', 'status']);
            $table->index(['participation_id', 'created_at']);
        });

        Schema::create('promotion_audit_heads', function (Blueprint $table) {
            $table->foreignId('campaign_id')->primary()->constrained('campaigns')->restrictOnDelete();
            $table->unsignedBigInteger('last_sequence')->default(0);
            $table->char('last_hash', 64)->default(str_repeat('0', 64));
            $table->timestamps();
        });

        Schema::create('win_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->restrictOnDelete();
            $table->unsignedBigInteger('sequence');
            $table->foreignId('win_id')->nullable()->constrained('wins')->nullOnDelete();
            $table->foreignId('participation_id')->nullable()->constrained('participations')->nullOnDelete();
            $table->char('actor_ref', 64)->nullable();
            $table->string('event_type', 64);
            $table->json('payload');
            $table->char('previous_hash', 64);
            $table->char('event_hash', 64)->unique();
            $table->dateTime('occurred_at');

            $table->unique(['campaign_id', 'sequence']);
            $table->index(['campaign_id', 'occurred_at']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::unprepared("CREATE TRIGGER promotion_win_events_no_update BEFORE UPDATE ON win_events FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Promotion audit events are immutable'");
            DB::unprepared("CREATE TRIGGER promotion_win_events_no_delete BEFORE DELETE ON win_events FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Promotion audit events are immutable'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS promotion_win_events_no_update');
            DB::unprepared('DROP TRIGGER IF EXISTS promotion_win_events_no_delete');
        }

        Schema::dropIfExists('win_events');
        Schema::dropIfExists('promotion_audit_heads');
        Schema::dropIfExists('wins');
        Schema::dropIfExists('participations');
        Schema::dropIfExists('prizes');
        Schema::dropIfExists('campaigns');
    }
};
