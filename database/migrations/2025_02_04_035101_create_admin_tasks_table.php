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
        Schema::create('admin_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_type'); // Typ der Aufgabe (z. B. "Rabattierung", "Auszahlung prüfen")
            $table->text('description')->nullable(); // Beschreibung der Aufgabe
            $table->tinyInteger('status')->default(0); // Status als Integer (0 = offen, 1 = in Bearbeitung, 2 = abgeschlossen)
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // Admin, der die Aufgabe bearbeitet
            $table->foreignId('shelf_rental_id')->nullable()->constrained('shelf_rentals')->cascadeOnDelete(); // Verknüpfung zur Regalmiete
            $table->timestamp('completed_at')->nullable(); // Zeitpunkt der Erledigung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_tasks');
    }
};
