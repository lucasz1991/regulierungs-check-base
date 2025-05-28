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
        Schema::create('rating_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // z.B. "Verzögerung"
            $table->string('tag')->unique(); // z.B. "delay"
            $table->text('description')->nullable(); // Optional detaillierte Erklärung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_tags');
    }
};
