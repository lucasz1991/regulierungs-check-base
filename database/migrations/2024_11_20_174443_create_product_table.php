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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers'); 
            $table->foreignId('shelf_rental_id')->constrained('shelf_rentals'); 
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->text('size')->nullable();
            $table->json('images')->nullable(); // Array von Bildern
            $table->string('category')->nullable(); // Kategorie des Produkts
            $table->string('tags')->nullable(); // Kategorie des Produkts
            $table->string('age_recommendation'); // Altersempfehlung für das Produkt
            $table->string('status')->default('1'); // Status des Produkts (Standard: Entwurf)
            $table->string('cash_register_id')->nullable(); // cash_register_id des Produkts (Standard: null)
            $table->integer('views')->default('0'); // Status des Produkts (Standard: Entwurf)
            $table->timestamp('published_at')->nullable(); // Veröffentlichungsdatum
            $table->softDeletes();
            $table->timestamps();
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
