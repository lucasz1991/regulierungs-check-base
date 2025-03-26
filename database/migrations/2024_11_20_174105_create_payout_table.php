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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('shelf_rental_id')->nullable(); // Verknüpfung zu einer Regalbuchung
            $table->decimal('amount', 10, 2);
            $table->boolean('status')->default(false); 
            $table->json('payout_details')->nullable(); // JSON-Feld für Bankverbindung oder PayPal-Daten
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('shelf_rental_id')->references('id')->on('shelf_rentals')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
