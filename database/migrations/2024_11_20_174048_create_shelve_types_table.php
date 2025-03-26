<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shelve_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('width'); // Breite des Regals
            $table->integer('length'); // Länge des Regals
            $table->text('description')->nullable(); // Beschreibung des Regaltyps
            $table->string('image_path')->nullable(); // Pfad zum Bild des Regaltyps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shelve_types');
    }
};
