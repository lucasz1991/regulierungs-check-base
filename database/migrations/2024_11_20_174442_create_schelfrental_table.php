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
        Schema::create('shelf_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shelf_id')->constrained('shelves')->restrictOnDelete(); 
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete(); 
            $table->date('rental_start');
            $table->date('rental_end');
            $table->float('total_price');
            $table->string('payment_method');
            $table->integer('period');
            $table->integer('status')->default('1');
            $table->string('rental_bill_url')->nullable();
            $table->string('complete_bill_url')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['shelf_id', 'rental_start', 'rental_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelf_rentals');
    }
};
