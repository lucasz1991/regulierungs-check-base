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
        Schema::create('detail_insurance_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('insurance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('insurance_subtype_id')->nullable()->constrained()->nullOnDelete();

            $table->string('type')->nullable();          // z. B. 'allgemein', 'subtyp', 'ai'
            $table->string('status')->default('pending'); // z. B. 'pending', 'approved', 'rejected'

            $table->decimal('fairness', 4, 2)->nullable();
            $table->decimal('speed', 4, 2)->nullable();
            $table->decimal('communication', 4, 2)->nullable();
            $table->decimal('transparency', 4, 2)->nullable();
            $table->decimal('total_score', 4, 2)->nullable();

            $table->text('ai_comment')->nullable();
            $table->json('ai_tags')->nullable();
            $table->text('admin_comment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_insurance_ratings');
    }
};
