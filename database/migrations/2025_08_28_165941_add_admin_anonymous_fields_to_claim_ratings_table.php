<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('claim_ratings', function (Blueprint $table) {
            if (!Schema::hasColumn('claim_ratings', 'admin_review')) {
                $table->json('admin_review')->nullable()->after('is_public');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claim_ratings', function (Blueprint $table) {
            if (Schema::hasColumn('claim_ratings', 'admin_review')) {
                $table->dropColumn('admin_review');
            }
        });
    }
};
