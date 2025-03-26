<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shelves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retail_space_id')->constrained('retail_spaces')->onDelete('cascade');
            $table->integer('shelve_type_id');
            $table->integer('floor_number');
            $table->bigInteger('shelve_id');
            $table->float('position_x'); // X position of the shelve within the retail space
            $table->float('position_y'); // Y position of the shelve within the retail space
            $table->softDeletes();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('shelves');
    }
};
