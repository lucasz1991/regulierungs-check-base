<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claim_ratings', function (Blueprint $table) {
            $table->json('data')->nullable()->after('admin_review');
        });
    }

    public function down(): void
    {
        Schema::table('claim_ratings', function (Blueprint $table) {
            $table->dropColumn('data');
        });
    }
};
