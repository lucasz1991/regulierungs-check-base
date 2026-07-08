<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('files')) {
            return;
        }

        Schema::table('files', function (Blueprint $table) {
            if (! Schema::hasColumn('files', 'filepool_id')) {
                $table->unsignedBigInteger('filepool_id')->nullable()->index()->after('id');
            }

            if (! Schema::hasColumn('files', 'disk')) {
                $table->string('disk', 32)->default('private')->index()->after('path');
            }

            if (! Schema::hasColumn('files', 'type')) {
                $table->string('type', 50)->default('default')->index()->after('mime_type');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('files')) {
            return;
        }

        Schema::table('files', function (Blueprint $table) {
            foreach (['filepool_id', 'disk'] as $column) {
                if (Schema::hasColumn('files', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
