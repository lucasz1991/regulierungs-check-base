<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Manuell gepflegte Lesezeit fuer News.
 *
 * Bisher wurde die Lesezeit in der Base ausschliesslich aus dem Text
 * geschaetzt, waehrend die Detailseite den im PageBuilder getippten Wert
 * zeigte - beide Werte konnten beliebig auseinanderlaufen. Ab hier ist der
 * hier gepflegte Wert massgeblich; bleibt er leer, greift weiterhin die
 * Schaetzung.
 *
 * Nullable, damit bestehende Beitraege unveraendert weiterlaufen.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedSmallInteger('reading_time_minutes')
                ->nullable()
                ->after('excerpt');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('reading_time_minutes');
        });
    }
};
