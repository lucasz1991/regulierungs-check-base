<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('mails', 'type')) {
            Schema::table('mails', function (Blueprint $table) {
                $table->string('type')->default('message')->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('mails', 'type')) {
            Schema::table('mails', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
