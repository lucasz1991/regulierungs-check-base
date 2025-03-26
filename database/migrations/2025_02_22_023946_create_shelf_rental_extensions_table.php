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
        Schema::create('shelf_rental_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shelf_rental_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Kunde oder Admin
            $table->date('previous_end_date'); // Enddatum vor der Verlängerung
            $table->date('new_end_date'); // Enddatum nach der Verlängerung
            $table->decimal('amount_paid', 8, 2)->nullable(); // Falls bezahlt, Betrag
            $table->boolean('is_admin')->default(false); // true = Admin-Verlängerung, false = Kunde
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null'); // Verknüpfung zu einer Rechnung
            $table->json('extension_details')->nullable(); // JSON-Feld für Zusatzinfos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelf_rental_extensions');
    }
};
