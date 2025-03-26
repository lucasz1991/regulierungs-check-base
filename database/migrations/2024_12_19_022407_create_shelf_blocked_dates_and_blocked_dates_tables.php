<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShelfBlockedDatesAndBlockedDatesTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tabelle für einzelne blockierte Regale
        Schema::create('shelf_blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shelf_id');
            $table->unsignedBigInteger('retail_space_id'); // Verkaufsflächen-ID hinzufügen
            $table->date('blocked_date');
            $table->timestamps();

            $table->foreign('retail_space_id')->references('id')->on('retail_spaces')->onDelete('cascade');
            $table->unique(['shelf_id', 'retail_space_id', 'blocked_date']);
        });

        // Tabelle für Tage, an denen alle Regale belegt sind
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('retail_space_id'); // Verkaufsflächen-ID hinzufügen
            $table->date('blocked_date');
            $table->integer('blocked_period'); // Neue Spalte für Zeitraum (z. B. 7, 14 oder 21 Tage)
            $table->timestamps();

            $table->foreign('retail_space_id')->references('id')->on('retail_spaces')->onDelete('cascade');
            $table->unique(['retail_space_id', 'blocked_date', 'blocked_period']); // Einzigartige Kombination
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('blocked_dates');
        Schema::dropIfExists('shelf_blocked_dates');
    }
}
