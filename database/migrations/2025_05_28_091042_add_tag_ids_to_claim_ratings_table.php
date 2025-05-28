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
            $table->json('tag_ids')->nullable()->after('rating_score'); // oder nach einem anderen passenden Feld
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claim_ratings', function (Blueprint $table) {
            //
        });
    }
};
