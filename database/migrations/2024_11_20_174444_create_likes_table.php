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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete(); // Kunde, der das Like gibt
            $table->foreignId('product_id')->nullable()->constrained('products')->restrictOnDelete(); // Produkt, das geliked wird
            $table->foreignId('shelf_id')->nullable()->constrained('shelves')->restrictOnDelete(); // Regal, das geliked wird
            $table->foreignId('liked_customer_id')->nullable()->constrained('customers')->restrictOnDelete(); // Verkäufer, der geliked wird
            $table->timestamps();

            // Sicherstellen, dass ein Like immer nur ein Ziel hat (Produkt, Regal oder Verkäufer)
            $table->unique(['customer_id', 'product_id', 'shelf_id', 'liked_customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
