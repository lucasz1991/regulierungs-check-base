<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shelf_rentals', function (Blueprint $table) {
            $table->unsignedTinyInteger('discount')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('shelf_rentals', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }
    
};
