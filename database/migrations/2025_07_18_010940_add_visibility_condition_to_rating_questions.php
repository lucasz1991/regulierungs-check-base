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
        Schema::table('rating_questions', function (Blueprint $table) {
            $table->json('visibility_condition')->nullable()->after('tags'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rating_questions', function (Blueprint $table) {
            $table->dropColumn('visibility_condition');
        });
    }
};
