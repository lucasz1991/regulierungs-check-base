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
        Schema::create('admin_shelf_blocked_dates', function (Blueprint $table) {
            $table->id(); // Primärschlüssel
            $table->foreignId('shelve_id')->constrained()->onDelete('cascade'); // Fremdschlüssel für Shelves
            $table->foreignId('retail_space_id')->constrained()->onDelete('cascade'); // Fremdschlüssel für RetailSpaces
            $table->date('start_date'); // Startdatum des gesperrten Zeitraums
            $table->date('end_date'); // Enddatum des gesperrten Zeitraums
            $table->timestamps(); // Standard Timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_shelf_blocked_dates');
    }
};
