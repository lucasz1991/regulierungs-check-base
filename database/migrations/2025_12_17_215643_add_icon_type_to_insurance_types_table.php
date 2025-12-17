<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_types', function (Blueprint $table) {
            $table
                ->string('icon_type')
                ->default('svg')
                ->after('icon_svg');
        });
    }

    public function down(): void
    {
        Schema::table('insurance_types', function (Blueprint $table) {
            $table->dropColumn('icon_type');
        });
    }
};
