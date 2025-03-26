<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('type'); // 'percentage' oder 'amount'
            $table->decimal('value', 10, 2);
            
            // Neue Spalten für Bonus-Kriterien
            $table->date('valid_from')->nullable(); // Bonus gültig von
            $table->date('valid_until')->nullable(); // Bonus gültig bis
            $table->date('booking_start_from')->nullable(); // Buchungsstart von
            $table->date('booking_start_until')->nullable(); // Buchungsstart bis

            $table->date('booking_end_from')->nullable(); // BuchungsEnde von
            $table->date('booking_end_until')->nullable(); // BuchungsEnde bis

            // Buchungsperioden
            $table->json('periods')->nullable();
            
            $table->enum('customer_requirement', ['new', 'existing', 'all'])->nullable();
            
            $table->integer('validity_period')->nullable(); // in Tagen
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('status')->default(1);
            $table->boolean('is_redeemable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
