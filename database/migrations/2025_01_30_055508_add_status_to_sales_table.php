<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    /**
     * Status:
     *
     *      1 = OK
     *      2 = Paid out
     *      3 = Storniert
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->default(1)
                ->after('sale_price');

            $table->decimal('net_sale_price', 10, 2)
                ->nullable()
                ->after('sale_price'); // Neuer Wert ohne Provision
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['status', 'net_sale_price']);
        });
    }
};
