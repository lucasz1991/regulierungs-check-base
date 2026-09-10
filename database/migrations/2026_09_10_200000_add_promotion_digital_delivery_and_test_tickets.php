<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promotion_tickets', function (Blueprint $table): void {
            $table->uuid('public_id')->nullable()->after('id');
            $table->string('ticket_type', 16)->default('regular')->index()->after('status');
            $table->foreignId('test_issued_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->foreignId('test_reset_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('test_reset_at')->nullable();
            $table->string('test_reset_reason', 255)->nullable();
        });

        DB::table('promotion_tickets')->orderBy('id')->each(function (object $ticket): void {
            DB::table('promotion_tickets')->where('id', $ticket->id)->update(['public_id' => (string) Str::uuid()]);
        });

        Schema::table('promotion_tickets', function (Blueprint $table): void {
            $table->unique('public_id');
            $table->dropUnique('promotion_tickets_campaign_id_user_id_unique');
            $table->foreignId('participation_id')->nullable()->change();
        });

        Schema::table('promotion_spin_results', function (Blueprint $table): void {
            $table->boolean('is_test')->default(false)->index()->after('is_final');
            $table->string('digital_delivery_status', 32)->default('not_applicable')->index()->after('mail_status');
            $table->foreignId('digital_delivery_approved_by')->nullable()->after('fulfilled_by')->constrained('users')->nullOnDelete();
            $table->dateTime('digital_delivery_approved_at')->nullable()->after('digital_delivery_approved_by');
        });

        Schema::create('promotion_gift_codes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->restrictOnDelete();
            $table->foreignId('prize_id')->constrained('prizes')->restrictOnDelete();
            $table->foreignId('spin_result_id')->nullable()->unique()->constrained('promotion_spin_results')->nullOnDelete();
            $table->text('code_encrypted');
            $table->char('code_fingerprint', 64)->unique();
            $table->string('status', 20)->default('available')->index();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('reserved_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->foreignId('disabled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('disabled_at')->nullable();
            $table->timestamps();
            $table->index(['prize_id', 'status', 'id']);
        });

        Schema::create('promotion_notification_deliveries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('spin_result_id')->constrained('promotion_spin_results')->cascadeOnDelete();
            $table->string('type', 32);
            $table->string('status', 16)->default('open')->index();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('failed_at')->nullable();
            $table->dateTime('last_attempted_at')->nullable();
            $table->char('error_digest', 64)->nullable();
            $table->timestamps();
            $table->unique(['spin_result_id', 'type']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE promotion_tickets ADD CONSTRAINT promotion_tickets_type_check CHECK (ticket_type IN ('regular', 'test'))");
            DB::statement("ALTER TABLE promotion_spin_results ADD CONSTRAINT promotion_spin_results_delivery_check CHECK (digital_delivery_status IN ('not_applicable', 'awaiting_approval', 'awaiting_profile', 'awaiting_code', 'reserved', 'sent', 'failed'))");
            DB::statement("ALTER TABLE promotion_gift_codes ADD CONSTRAINT promotion_gift_codes_status_check CHECK (status IN ('available', 'reserved', 'sent', 'failed', 'disabled'))");
            DB::statement("ALTER TABLE promotion_notification_deliveries ADD CONSTRAINT promotion_notification_deliveries_status_check CHECK (status IN ('open', 'sent', 'failed'))");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE promotion_notification_deliveries DROP CONSTRAINT promotion_notification_deliveries_status_check');
            DB::statement('ALTER TABLE promotion_gift_codes DROP CONSTRAINT promotion_gift_codes_status_check');
            DB::statement('ALTER TABLE promotion_spin_results DROP CONSTRAINT promotion_spin_results_delivery_check');
            DB::statement('ALTER TABLE promotion_tickets DROP CONSTRAINT promotion_tickets_type_check');
        }
        Schema::dropIfExists('promotion_notification_deliveries');
        Schema::dropIfExists('promotion_gift_codes');
        Schema::table('promotion_spin_results', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('digital_delivery_approved_by');
            $table->dropColumn(['is_test', 'digital_delivery_status', 'digital_delivery_approved_at']);
        });
        Schema::table('promotion_tickets', function (Blueprint $table): void {
            $table->dropUnique(['public_id']);
            $table->dropConstrainedForeignId('test_issued_by');
            $table->dropConstrainedForeignId('test_reset_by');
            $table->dropColumn(['public_id', 'ticket_type', 'test_reset_at', 'test_reset_reason']);
            $table->unique(['campaign_id', 'user_id']);
            $table->foreignId('participation_id')->nullable(false)->change();
        });
    }
};
