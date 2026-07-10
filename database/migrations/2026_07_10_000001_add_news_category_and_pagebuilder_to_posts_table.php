<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('news_category_id')->nullable()->after('category_id')
                ->constrained('news_categories')->nullOnDelete();
            $table->foreignId('pagebuilder_project_id')->nullable()->after('news_category_id')
                ->constrained('pagebuilder_projects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pagebuilder_project_id');
            $table->dropConstrainedForeignId('news_category_id');
        });
    }
};
