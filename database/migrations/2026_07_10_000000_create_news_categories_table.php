<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 20)->default('#2563EB');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('news_categories')->insert([
            ['name' => 'Urteil', 'slug' => 'urteil', 'color' => '#7C3AED', 'icon' => 'fa-scale-balanced', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Markt & Daten', 'slug' => 'markt-daten', 'color' => '#059669', 'icon' => 'fa-chart-line', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kfz', 'slug' => 'kfz', 'color' => '#EA580C', 'icon' => 'fa-car', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ratgeber', 'slug' => 'ratgeber', 'color' => '#2563EB', 'icon' => 'fa-lightbulb', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('news_categories');
    }
};
