<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Individuelle Layoutwerte der generierten Social-Media-Bilder einer News.
 *
 * Nullable laesst bestehende News unveraendert: Der Admin und der Renderer
 * ergaenzen fehlende Werte mit der bisherigen Standardgestaltung.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->json('social_image_settings')
                ->nullable()
                ->after('images');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('social_image_settings');
        });
    }
};
