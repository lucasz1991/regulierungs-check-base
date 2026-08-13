<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wins', function (Blueprint $table): void {
            $table->string('fulfillment_mode_snapshot', 32)->nullable()->after('prize_name_snapshot');
        });

        // Backfill defensively when this additive migration is installed over
        // data created during a staged deployment.
        DB::table('wins')->select(['id', 'prize_id'])->orderBy('id')->chunkById(500, function ($wins): void {
            $modes = DB::table('prizes')
                ->whereIn('id', $wins->pluck('prize_id')->all())
                ->pluck('fulfillment_mode', 'id');

            foreach ($wins as $win) {
                DB::table('wins')->where('id', $win->id)->update([
                    'fulfillment_mode_snapshot' => $modes[$win->prize_id] ?? null,
                ]);
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE wins MODIFY fulfillment_mode_snapshot VARCHAR(32) NOT NULL');
            DB::statement("ALTER TABLE prizes ADD CONSTRAINT promotion_prizes_quota_check CHECK (reserved_count <= quota)");
            DB::statement("ALTER TABLE prizes ADD CONSTRAINT promotion_prizes_mode_check CHECK (fulfillment_mode IN ('onsite_staff', 'external_admin'))");
            DB::statement("ALTER TABLE wins ADD CONSTRAINT promotion_wins_status_check CHECK (status IN ('issued', 'bound', 'confirmed', 'fulfilled', 'disputed', 'expired', 'cancelled'))");
            DB::statement("ALTER TABLE wins ADD CONSTRAINT promotion_wins_mode_check CHECK (fulfillment_mode_snapshot IN ('onsite_staff', 'external_admin'))");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE prizes DROP CONSTRAINT promotion_prizes_quota_check');
            DB::statement('ALTER TABLE prizes DROP CONSTRAINT promotion_prizes_mode_check');
            DB::statement('ALTER TABLE wins DROP CONSTRAINT promotion_wins_status_check');
            DB::statement('ALTER TABLE wins DROP CONSTRAINT promotion_wins_mode_check');
        }

        Schema::table('wins', function (Blueprint $table): void {
            $table->dropColumn('fulfillment_mode_snapshot');
        });
    }
};
