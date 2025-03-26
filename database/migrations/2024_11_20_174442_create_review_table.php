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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewed_customer_id')->constrained('customers')->restrictOnDelete(); // Der Kunde, der die Bewertung erhält
            $table->foreignId('creator_customer_id')->constrained('customers')->restrictOnDelete(); // Der Kunde, der die Bewertung erstellt
            $table->text('review_text');
            $table->integer('rating');
            $table->datetime('date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
