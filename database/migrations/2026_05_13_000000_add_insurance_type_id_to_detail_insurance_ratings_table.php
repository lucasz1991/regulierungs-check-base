<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_insurance_ratings', function (Blueprint $table) {
            $table->foreignId('insurance_type_id')
                ->nullable()
                ->after('insurance_id')
                ->constrained()
                ->nullOnDelete();

            $table->index([
                'insurance_id',
                'insurance_type_id',
                'insurance_subtype_id',
            ], 'detail_ratings_insurance_type_subtype_index');
        });
    }

    public function down(): void
    {
        Schema::table('detail_insurance_ratings', function (Blueprint $table) {
            $table->dropIndex('detail_ratings_insurance_type_subtype_index');
            $table->dropConstrainedForeignId('insurance_type_id');
        });
    }
};
